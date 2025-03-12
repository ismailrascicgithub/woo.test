/**
 * Some default parameters for Swiper slider
 * Full api: https://swiperjs.com/swiper-api
 */

export const exampleProps = {
  direction: 'horizontal',
  slidesPerView: 1,
  spaceBetween: 0,
  updateOnWindowResize: true,
  loop: true,
  speed: 500,
  resistanceRatio: 0.9,
  threshold: 20,
  effect: 'fade',
  fadeEffect: {
    crossFade: true
  },
  navigation: {
    nextEl: '.swiper-container-test .swiper-button-next',
    prevEl: '.swiper-container-test .swiper-button-prev'
  }
}
