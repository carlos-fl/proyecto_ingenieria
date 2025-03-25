import { hideLoadingComponent, showFailModal, showLoadingComponent, showSuccessModal } from "./utlis.mjs"
import { isValidDni, isValidCalification, isValidExamName } from "./validator.mjs"
import { Request } from "./request.mjs"

export class CSV {
  /**
   * 
   * @param {string} csvInputId
   * @param {Array} columns 
   * this are the columns acceptable in csv file
   * @param {errorModalID} string
   * the one in tag-id
   * @param {string} successModalID
   * the one in tag-id
   */
  static getCsvContent(csvInputId, columns, errorModalID, loadingID, successModalID) {
    const input = document.getElementById(csvInputId)
    input.addEventListener('change', () => {
      try {
        const csv = input.files[0]

        if (!csv || csv.type != "text/csv") {
          hideLoadingComponent(loadingID)
          showFailModal(errorModalID)
          return
        }

        const fileReader = new FileReader()
        fileReader.readAsText(csv)

        fileReader.addEventListener('loadstart', () => {
          showLoadingComponent(loadingID)
        })

        fileReader.addEventListener('loadend', () => {
            hideLoadingComponent(loadingID)
        })

        fileReader.addEventListener('load', async () => {
          showLoadingComponent(loadingID)
          const csvText = fileReader.result
          if (!this.#isValidFormat(csvText, columns)) {
            hideLoadingComponent(loadingID)
            showFailModal(errorModalID)
          } else {
            if (!this.#isValidData(csvText)) {
              hideLoadingComponent(loadingID)
              showFailModal(errorModalID)
              return
            }
            
            const form = new FormData()
            form.append("file", csv)
            
            try {
              const URL = "/api/administrator/controllers/uploadAdmissionExams.php"
              const res = await Request.fetch(URL, "POST", form, false)
              hideLoadingComponent(loadingID)
              if (res.status == 'success') {
                showSuccessModal(successModalID)
              } else {
                showFailModal(errorModalID)
              }
              
            } catch(err) {
              hideLoadingComponent(loadingID)
              showFailModal(errorModalID)
            }
          }

        })

      } catch(err) {
        showFailModal(errorModalID)
      }
    })
  } 
  /**
   * 
   * @param {string} csvContent
   * @param {array} columns
   */
  static #isValidFormat(csvContent, columns) {
    const cols = csvContent.split('\n')[0].split(',')
    if (JSON.stringify(cols) != JSON.stringify(columns)) {
      return false
    }
    return true
  }

  /**
   * 
   * @param {string} csvContent 
   */
  static #isValidData(csvContent) {
    const data = csvContent.split('\n').splice(0, 1)
    data.forEach(record => {
      const recordInfo = record.split(',')
        if (!isValidDni(recordInfo[0]) || !isValidExamName(recordInfo[1]) || !isValidCalification(recordInfo[2]))
          return false
    })
    return true
  }
}