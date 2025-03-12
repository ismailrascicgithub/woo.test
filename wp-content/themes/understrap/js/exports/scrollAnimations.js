import { gsap } from 'gsap'
import { ScrollTrigger } from 'gsap/ScrollTrigger'

/**
 * Imports scrolltriggered fade animations powered by GSAP and ScrollTrigger plugin
 * @constructor
 * @returns {void} 
 */

export default function ScrollAnimations() {
  this.val = {
    fadeLeft: '.fade-left',
    fadeRight: '.fade-right',
    fadeBottom: '.fade-bottom',
    fade: '.fade'
  }

  this.init = function() {
    this.attachScrollTrigger()
  }

  this.attachScrollTrigger = function() {
    gsap.utils.toArray(this.val.fadeLeft).forEach((item) => {
      gsap.from(item, {
        x: 20,
        opacity: 0,
        duration: 1.5,
        ease: 'expo.out',
        scrollTrigger: {
          trigger: item,
          start: 'top bottom-=60',
          end: 'bottom top',
          // markers: true,
          once: true
        }
      })
    })

    gsap.utils.toArray(this.val.fadeRight).forEach((item) => {
      gsap.from(item, {
        x: 20,
        opacity: 0,
        duration: 1.5,
        ease: 'expo.out',
        scrollTrigger: {
          trigger: item,
          start: 'top bottom-=60',
          end: 'bottom top',
          // markers: true,
          once: true
        }
      })
    })

    gsap.utils.toArray(this.val.fadeBottom).forEach((item) => {
      gsap.from(item, {
        y: 20,
        opacity: 0,
        duration: 1.5,
        ease: 'power4.out',
        scrollTrigger: {
          trigger: item,
          start: 'top bottom-=60',
          end: 'bottom top',
          // markers: true,
          once: true
        }
      })
    })

    gsap.utils.toArray(this.val.fade).forEach((item) => {
      gsap.from(item, {
        opacity: 0,
        duration: 1,
        ease: 'Power1.easeIn',
        scrollTrigger: {
          trigger: item,
          start: 'top bottom-=60',
          end: 'bottom top',
          // markers: true,
          once: true
        }
      })
    })
  }

  this.init()
}
