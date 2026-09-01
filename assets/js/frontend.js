/**
 * Elementor Dynamic Toolkit - Frontend Controller
 */

(function () {
	'use strict';

	const EDTFrontend = {
		init: function () {
			this.initLoadMore();
			this.initContentSwitcher();
		},

		initLoadMore: function () {
			document.addEventListener('click', function (e) {
				const btn = e.target.closest('.edt-button--load-more, .edt-pagination__load-more-btn');
				if (!btn || btn.classList.contains('is-loading')) {
					return;
				}

				e.preventDefault();
				const widget = btn.closest('.edt-widget');
				if (!widget) return;

				const container = widget.querySelector('.edt-dynamic-grid__container, .edt-dynamic-query__items');
				if (!container) return;

				const page = parseInt(btn.getAttribute('data-page'), 10) || 1;
				const maxPage = parseInt(btn.getAttribute('data-max-page'), 10) || 1;

				btn.classList.add('is-loading');

				const restUrl = (window.EDTFrontendData && window.EDTFrontendData.restUrl)
					? window.EDTFrontendData.restUrl + '/query'
					: '/wp-json/edt/v1/query';

				fetch(restUrl, {
					method: 'POST',
					headers: {
						'Content-Type': 'application/json',
						'X-WP-Nonce': (window.EDTFrontendData && window.EDTFrontendData.nonce) ? window.EDTFrontendData.nonce : ''
					},
					body: JSON.stringify({
						page: page,
						template: widget.classList.contains('edt-dynamic-grid') ? 'widgets/dynamic-post-grid/item' : 'widgets/dynamic-query/item',
						settings: {
							post_type: widget.getAttribute('data-post-type') || 'post',
							posts_per_page: parseInt(widget.getAttribute('data-posts-per-page'), 10) || 6
						}
					})
				})
				.then(function (res) { return res.json(); })
				.then(function (response) {
					btn.classList.remove('is-loading');
					if (response.success && response.data && response.data.html) {
						container.insertAdjacentHTML('beforeend', response.data.html);
						btn.setAttribute('data-page', page + 1);

						if (page >= maxPage || !response.data.has_more) {
							btn.style.display = 'none';
						}
					}
				})
				.catch(function () {
					btn.classList.remove('is-loading');
				});
			});
		},

		initContentSwitcher: function () {
			document.addEventListener('click', function (e) {
				const btn = e.target.closest('.edt-content-switcher__btn');
				if (!btn) return;

				const widget = btn.closest('.edt-content-switcher');
				if (!widget) return;

				const targetId = btn.getAttribute('data-target');
				const allBtns = widget.querySelectorAll('.edt-content-switcher__btn');
				const allPanes = widget.querySelectorAll('.edt-content-switcher__pane');

				allBtns.forEach(function (b) {
					b.classList.remove('is-active');
					b.setAttribute('aria-selected', 'false');
				});
				allPanes.forEach(function (p) {
					p.classList.remove('is-active');
					p.style.display = 'none';
				});

				btn.classList.add('is-active');
				btn.setAttribute('aria-selected', 'true');

				const activePane = widget.querySelector('#' + targetId);
				if (activePane) {
					activePane.classList.add('is-active');
					activePane.style.display = 'block';
				}
			});
		}
	};

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', function () {
			EDTFrontend.init();
		});
	} else {
		EDTFrontend.init();
	}

	window.EDTFrontend = EDTFrontend;
})();
