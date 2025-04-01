import { Request } from "../../js/modules/request.mjs";

export async function getCenters() {
  const URL = "/api/resources/controllers/regionalCenters.php"
  try {
    const data = await Request.fetch(URL, 'GET')
    return data.data
  } catch(err) {
    return []
  }
}

export async function getMajors() {
  const URL = "/api/resources/controllers/majorsData.php"
  try {
    const data = await Request.fetch(URL, 'GET')
    return data.data
  } catch(err) {
    return []
  }
}