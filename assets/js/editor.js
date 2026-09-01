/**
 * Elementor Dynamic Toolkit - Editor Scripts
 */

(function ($) {
	'use strict';

	const EDTEditor = {
		init: function () {
			$(document).on('input', '.edt-async-select-search', this.debounce(this.handleAsyncSearch, 300));
			$(document).on('click', '.edt-add-condition-btn', this.handleAddCondition);
			$(document).on('click', '.edt-condition-rule-remove', this.handleRemoveCondition);
		},

		debounce: function (func, wait) {
			let timeout;
			return function () {
				const context = this;
				const args = arguments;
				clearTimeout(timeout);
				timeout = setTimeout(function () {
					func.apply(context, args);
				}, wait);
			};
		},

		handleAsyncSearch: function (e) {
			const $input = $(e.target);
			const $select = $input.siblings('select.edt-async-select-field');
			const query = $input.val();
			const source = $select.data('source') || 'posts';
			const postType = $select.data('post-type') || 'post';

			if (!window.EDT_Editor_Data || !window.EDT_Editor_Data.restUrl) {
				return;
			}

			$.ajax({
				url: window.EDT_Editor_Data.restUrl + '/search',
				method: 'GET',
				data: {
					query: query,
					source: source,
					post_type: postType
				},
				headers: {
					'X-WP-Nonce': window.EDT_Editor_Data.nonce
				},
				success: function (response) {
					if (response.success && response.data) {
						$select.empty();
						response.data.forEach(function (item) {
							$select.append(new Option(item.text, item.id));
						});
					}
				}
			});
		},

		handleAddCondition: function (e) {
			e.preventDefault();
			const $container = $(this).closest('.edt-condition-builder-control');
			const $list = $container.find('.edt-condition-rules-list');

			let optionsHtml = '';
			if (window.EDT_Editor_Data && window.EDT_Editor_Data.conditions) {
				Object.keys(window.EDT_Editor_Data.conditions).forEach(function (key) {
					const cond = window.EDT_Editor_Data.conditions[key];
					optionsHtml += '<option value="' + cond.id + '">' + cond.title + '</option>';
				});
			}

			const ruleHtml = `
				<div class="edt-condition-rule-item">
					<select class="edt-rule-condition-select">
						${optionsHtml}
					</select>
					<select class="edt-rule-operator-select">
						<option value="is">Is</option>
						<option value="is_not">Is Not</option>
					</select>
					<input type="text" class="edt-rule-value-input" placeholder="Value..." />
					<span class="edt-condition-rule-remove" title="Remove">&times;</span>
				</div>
			`;

			$list.append(ruleHtml);
		},

		handleRemoveCondition: function (e) {
			e.preventDefault();
			$(this).closest('.edt-condition-rule-item').remove();
		}
	};

	$(document).ready(function () {
		EDTEditor.init();
	});

	window.EDTEditor = EDTEditor;
})(jQuery);
