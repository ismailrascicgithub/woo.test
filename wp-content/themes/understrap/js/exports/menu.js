/**
 * Create a menu object with event listeners for open and close buttons
 * The function will 1) add 'menu-is-active' class to menu and its buttons, when the menu is open
 * as well as 2) 'overflow-hidden' class to the body: both classes must be accompanied with css 
 * @constructor
 * @param {String} menu Selector for menu element that we want to dispaly
 * @param {String} openMenuButton Selector for button that opens the menu
 * @param {String} closeMenuButton Selector for button that closes the menu
 * @returns {void} 
 */

export default function Menu(menu, openMenuButton, closeMenuButton) {
  this.body = document.getElementsByTagName('body')[0]
  this.menu = document.querySelector(menu)
  this.menuBtnOpen = document.querySelector(openMenuButton)
  this.menuBtnClose = document.querySelector(closeMenuButton)

  this.init = function() {
    this.attachListeners()
  }

  this.attachListeners = function() {
    this.menuBtnOpen.addEventListener('click', () => this.openMenu())
    this.menuBtnClose.addEventListener('click', () => this.closeMenu())
  }

  this.openMenu = function() {
    this.menu.classList.add('menu-is-active')
    this.menuBtnOpen.classList.add('menu-is-active')
    this.body.classList.add('overflow-hidden')
  }

  this.closeMenu = function() {
    this.menu.classList.remove('menu-is-active')
    this.menuBtnOpen.classList.remove('menu-is-active')
    this.body.classList.remove('overflow-hidden')
  }

  this.init()
}
