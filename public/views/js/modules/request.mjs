export class Request {
  /**
   * 
   * @param {string} URL - API endpoint 
   * @param {string} method - HTTP method (GET, POST, PUT, DELETE)
   * @param {object|null} body - Request payload (if method is not GET)
   * @returns {Promise<JSON>} - Parsed response from the server
   */
  static async fetch(URL, method = "GET", body = null) {
    try {
      const options = {
        method,
        headers: {
          "Content-Type": "application/json"
        },
      };

      // Only add body for methods that support it
      if (body && method !== "GET") {
        options.body = JSON.stringify(body);
      }

      const response = await fetch(URL, options);
      const data = await response.json()

      if (!response.ok) {
        throw new Error(data.error.errorMessage)
      }

      return await response.json();
      
    } catch (err) {
      throw new Error(err.message);
    }
  }
}
