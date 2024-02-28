var page = require("webpage").create();
var address = "http://sharado.dev/it/landing-page";
var timeout = 20*1000;
console.log('starting')
page.open(address);


function getContent() {
    return page.evaluate(function() {
        return document.body.innerHTML;
    });
}

//page.onLoadFinished = function () {
  console.log('finished')
    setTimeout(function() {
       console.log(getContent());
    }, timeout);

//}



// var system = require('system');
// var url = system.args[1] || '';
// if(url.length > 0) {
//   var page = require('webpage').create();  
//   page.open(url, function (status) {
//     console.log(status)
//     if (status == 'success') {
//       var delay, checker = (function() {
//         var html = page.evaluate(function () {
//           var body = document.getElementsByTagName('body')[0];
//           //if(body.getAttribute('data-status') == 'ready') {
//             console.log('aaa')
//             return document.getElementsByTagName('html')[0].outerHTML;
//           //}
//         });
//         if(html) {
//           clearTimeout(delay);
//           console.log(html);
//           phantom.exit();
//         }
//       });
//       delay = setInterval(checker, 100);
//     }
//   });
// }

