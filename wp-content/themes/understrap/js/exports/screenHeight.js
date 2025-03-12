import { triggerOnWindowBreak } from './helpers'

export default function SetScreenHeight() {
  this.width = document.documentElement.clientWidth

  this.init = function() {
    this.setHeight()
  }

  this.setHeight = function() {
    const calculatedHeight = document.documentElement.clientHeight * 0.01
    document.documentElement.style.setProperty('--vh', `${calculatedHeight}px`)
  }

  this.addResizeListener = function() {
    window.addEventListener('resize', this.checkIfResized)
  }

  this.removeResizeListener = function() {
    window.removeEventListener('resize', this.checkIfResized)
  }

  // Avoids resize event on scroll (iOS bug)
  this.checkIfResized = function() {
    if (this.width !== document.documentElement.clientWidth) {
      this.setHeight()
      this.width = document.documentElement.clientWidth
    }
  }.bind(this)

  this.init()
}
