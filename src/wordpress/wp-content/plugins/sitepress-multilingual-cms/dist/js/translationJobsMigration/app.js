!function(t){var e={};function n(o){if(e[o])return e[o].exports;var r=e[o]={i:o,l:!1,exports:{}};return t[o].call(r.exports,r,r.exports,n),r.l=!0,r.exports}n.m=t,n.c=e,n.d=function(t,e,o){n.o(t,e)||Object.defineProperty(t,e,{enumerable:!0,get:o})},n.r=function(t){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(t,"__esModule",{value:!0})},n.t=function(t,e){if(1&e&&(t=n(t)),8&e)return t;if(4&e&&"object"==typeof t&&t&&t.__esModule)return t;var o=Object.create(null);if(n.r(o),Object.defineProperty(o,"default",{enumerable:!0,value:t}),2&e&&"string"!=typeof t)for(var r in t)n.d(o,r,function(e){return t[e]}.bind(null,r));return o},n.n=function(t){var e=t&&t.__esModule?function(){return t.default}:function(){return t};return n.d(e,"a",e),e},n.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},n.p="",n(n.s=2)}([function(t,e){t.exports=function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}},function(t,e){function n(t,e){for(var n=0;n<e.length;n++){var o=e[n];o.enumerable=o.enumerable||!1,o.configurable=!0,"value"in o&&(o.writable=!0),Object.defineProperty(t,o.key,o)}}t.exports=function(t,e,o){return e&&n(t.prototype,e),o&&n(t,o),t}},function(t,e,n){t.exports=n(3)},function(t,e,n){"use strict";n.r(e);var o=n(0),r=n.n(o),i=n(1),a=n.n(i),u=function(){function t(e){r()(this,t),this.ajaxUrl=e,this.migrationTrigger=document.querySelector(".wpml-start-jobs-migration"),this.results=document.querySelector(".wpml-js-jobs-migration-progress"),this.jobsMigrated=document.querySelector(".wpml-js-migrated-jobs"),this.jobsMigratedCount=0,this.totalJobs=document.querySelector(".wpml-js-total-jobs"),this.totalJobsCount=0,this.spinner=document.querySelector(".wpml-jobs-migration-spinner"),this.nonce=document.querySelector("#wpml_translation_jobs_migration").value}return a()(t,[{key:"attachEvents",value:function(){var t=this;this.migrationTrigger.addEventListener("click",(function(){return t.run()}))}},{key:"run",value:function(){this.migrationTrigger.disabled=!0,this.spinner.classList.add("is-active"),this.migrate()}},{key:"migrate",value:function(){var t=this;fetch(this.ajaxUrl,{method:"POST",headers:{"Content-Type":"application/x-www-form-urlencoded; charset=utf-8"},body:"action=wpml_translation_jobs_migration&nonce=".concat(this.nonce),credentials:"same-origin"}).then((function(t){return t.json()})).then((function(e){if(!e.success)throw e;t.results.style.display="block",t.jobsMigratedCount+=e.data.jobsMigrated,t.updateResults(t.jobsMigratedCount),t.totalJobsCount||(t.totalJobsCount=e.data.totalJobs,t.totalJobs.innerHTML=t.totalJobsCount),e.data.done?location.reload():t.migrate()})).catch((function(t){var e=document.querySelector(".wpml-job-migration-error");e&&(t.data&&(e.querySelector(".error-message").textContent=t.data),e.style.display="block")}))}},{key:"updateResults",value:function(t){this.jobsMigrated.innerHTML=t}}]),t}();document.addEventListener("DOMContentLoaded",(function(){return new u(ajaxurl).attachEvents()}))}]);