import { gsap } from 'gsap'

export default function Expandable(el, trigger, exclusive = false) {
  this.elements = document.querySelectorAll(el)
  this.exclusive = exclusive

  this.init = function() {
    this.attachListeners()
  }

  this.attachListeners = function() {
    for (let i = 0; i < this.elements.length; i++) {
      const triggerItem = this.elements[i].querySelector(trigger)
      triggerItem.addEventListener('click', () => this.handleClick(triggerItem))
    }
  }

  this.handleClick = function(trigger) {
    const openContentTrigger = document.querySelector(`${el}.content-visible .expandable-trigger`)
    if (this.exclusive && openContentTrigger && openContentTrigger !== trigger) {
      this.toggleContent(openContentTrigger)
    }
    this.toggleContent(trigger)
  }

  this.toggleContent = function(trigger) {
    const parent = trigger.closest(el)
    const child = parent.querySelector('.expandable-content')

    if (parent.classList.contains('content-hidden')) {
      gsap.set(child, {
        maxHeight: 9999,
        opacity: 1
      })
      parent.classList.remove('content-hidden')
      parent.classList.add('content-visible')
    } else {
      gsap.set(child, {
        maxHeight: 0,
        opacity: 0
      })
      parent.classList.add('content-hidden')
      parent.classList.remove('content-visible')
    }
  }

  this.init()
}
