export class Request {
  /**
   * 
   * @param {string} URL endpoint 
   * @param {string} method request method like GET, POST, PUT, DELETE
   * @param {JSON} body if method is different than GET
   */
  static async fetch(URL, method, body = null) {
    try {
      const endpointCall = await fetch(URL, {
        method: method,
        headers: {
          'Content-Type': "application/json"
        },
        body
      })

      const response = await endpointCall.json()
      return response

    } catch(err) {
      throw new Error('error while fetching data...')
    }
  }
}