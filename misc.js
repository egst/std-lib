/**
    Wait for the given time in ms.
    @param {number} time - Time in ms.
    @returns {Promise<undefined>}
*/
export const sleep = time => new Promise(resolve => void setTimeout(resolve, time) )
