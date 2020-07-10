/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 */
import Vue from 'vue';

let binded = [];

function handler(e) {
  binded.forEach((el) => {
    if (!el.node.contains(e.target)) {
      el.callback(e);
    }
  });
}

function addListener(node, callback) {
  if (!binded.length) {
    document.addEventListener('click', handler, false);
  }

  binded.push({node, callback});
}

function removeListener(node, callback) {
  binded = binded.filter((el) => {
    if (el.node !== node) {
      return true;
    }

    if (!callback) {
      return false;
    }

    return el.callback !== callback;
  });
  if (!binded.length) {
    document.removeEventListener('click', handler, false);
  }
}

Vue.directive('click-outside', {
  bind(el, binding) {
    removeListener(el, binding.value);
    if (typeof binding.value === 'function') {
      addListener(el, binding.value);
    }
  },
  update(el, binding) {
    if (binding.value !== binding.oldValue) {
      removeListener(el, binding.oldValue);
      addListener(el, binding.value);
    }
  },
  unbind(el, binding) {
    removeListener(el, binding.value);
  },
});
