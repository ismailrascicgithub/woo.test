//// Modules ////

import $ from 'jquery';
import gsap from 'gsap';
import ScrollTrigger from 'gsap/ScrollTrigger';
import Swiper from 'swiper/bundle';

import Expandable from './exports/expandable';
import Menu from './exports/menu';
import SetScreenHeight from './exports/screenHeight';
import ScrollAnimations from './exports/scrollAnimations';
import { triggerOnWindowBreak } from './exports/helpers';
import { exampleProps } from './exports/swiperProps';
import { __ } from '@wordpress/i18n';

//// GSAP ////

gsap.registerPlugin(ScrollTrigger);
const scrollAnimations = new ScrollAnimations();

/* Refresh ScrollTrigger */
ScrollTrigger.refresh();

//// Menu ////

// const menu = new Menu();

//// Sliders ////

// const exampleSlider = new Swiper('.swiper-example-container', exampleProps);

//// DOC load ////

document.addEventListener('DOMContentLoaded', function () {
	/* Remove preload class to allow transitions */
	document.body.classList.remove('loading');

	// Handle category filter click.
	$('.filter.categories .option').on('click', function () {
		$('.filter.categories .option').removeClass('active');
		$(this).addClass('active');

		let category = $(this).data('category');
		let tax = $('.filter.price .option.active').data('tax') || 'no';

		updateURL(category, tax);
		loadFilteredProducts(category, tax);
	});

	// Handle price filter click.
	$('.filter.price .option').on('click', function () {
		$('.filter.price .option').removeClass('active');
		$(this).addClass('active');

		let tax = $(this).data('tax');
		let category = $('.filter.categories .option.active').data('category') || 'all';

		updateURL(category, tax);
		loadFilteredProducts(category, tax);
	});

	// Listen for back/forward navigation.
	window.addEventListener('popstate', function () {
		const params = new URLSearchParams(window.location.search);
		const category = params.get('category') || 'all';
		const tax = params.get('tax') || 'no';

		// Update filter states.
		$('.filter.categories .option').removeClass('active');
		$('.filter.categories .option[data-category="' + category + '"]').addClass('active');
		$('.filter.price .option').removeClass('active');
		$('.filter.price .option[data-tax="' + tax + '"]').addClass('active');

		loadFilteredProducts(category, tax);
	});

	/**
	 * Load filtered products via AJAX.
	 *
	 * @param {string} category Selected category.
	 * @param {string} tax Selected tax option.
	 */
	function loadFilteredProducts(category, tax) {
		$('.products-container').html('<div class="preloader">' + __('Nalaganje...', 'understrap') + '</div>');

		$.ajax({
			url: ajaxurl,
			type: 'POST',
			dataType: 'json',
			data: {
				action: 'filter_products',
				category: category,
				tax: tax,
			},
			success: function (response) {
				if (response.success) {
					$('.products-container').html(response.data);
				} else {
					$('.products-container').html('<p>' + __('Napaka: ', 'understrap') + response.data + '</p>');
				}
			},
			error: function () {
				$('.products-container').html('<p>' + __('Napaka AJAX: Ni bilo mogoče naložiti izdelkov.', 'understrap') + '</p>');
			}
		});
	}

	/**
	 * Update the URL with selected filters.
	 *
	 * @param {string} category
	 * @param {string} tax
	 */
	function updateURL(category, tax) {
		const url = new URL(window.location);
		url.searchParams.set('category', category);
		url.searchParams.set('tax', tax);
		window.history.pushState({}, '', url);
	}
});
