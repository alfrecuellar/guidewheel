window._ = require('lodash');

require('bootstrap');

window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const { DateTime } = require("luxon");

window.DateTime = DateTime;