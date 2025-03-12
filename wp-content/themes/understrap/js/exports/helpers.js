/**
 * Call one or more functions on a breakpoint
 * @param {String} breakpoint Breakpoint number (in pixels)
 * @param {String} triggerOn Determine when to trigger: either on 'desktop' or 'mobile'
 * @param {Function[]} actions Array of one or more functions
 * @returns {void} 
 */

export function triggerOnWindowBreak(breakpoint, triggerOn, actions) {
  let screenBreak

  // Set screen above or below breakpoint for event to take place
  if (triggerOn === 'desktop') {
    screenBreak = window.matchMedia(`(min-width: ${breakpoint}px)`)
  } else {
    screenBreak = window.matchMedia(`(max-width: ${breakpoint}px)`)
  }

  // Attach listener
  screenBreak.addListener(function(screenBreak) {
    if (screenBreak.matches) {
      actions.forEach(function(action) {
        action()
      })
    }
  })

  // Call functions
  if (screenBreak.matches) {
    actions.forEach(function(action) {
      action()
    })
  }
}

/**
 * Append uploaded file name(s)
 * @param {String} inputId Id of the input with type='file'
 * @param {String} nameHolder Selector for element where span elements with file name are appended
 * @returns {void} 
 */

export function appendUploadName(inputId, nameHolder) {
  document.getElementById(inputId).addEventListener('change', function() {
    let loadedFiles = [
      ...document.getElementById(inputId).files
    ]
    if (loadedFiles) {
      document.querySelector(nameHolder).classList.add('active')

      loadedFiles.forEach((file) => {
        let fileNameEl = document.createElement('span')
        let fileNameText = document.createTextNode(file.name)
        fileNameEl.appendChild(fileNameText)
        document.querySelector(nameHolder).appendChild(fileNameEl)
      })
    }
  })
}
