!function(e){e.fn.ihavecookies=function(n){var t=e(this),r=e.extend({cookieTypes:[{type:"Postavke",value:"preferences",description:"To su kolačići koji se odnose na postavke web-lokacije, npr. prisjećajući se vašeg korisničkog imena, valute itd."},{type:"Analitika",value:"analytics",description:"Kolačići - Google Analytics"},{type:"Marketing",value:"marketing",description:"Kolačići vezani uz marketing, npr. biltene, društvene medije itd"}],title:"Kolačići i privatnost",message:"Ova web stranica koristi kolačiće kako bi osigurala punu funkcionalnost ovih Internet stranica i omogućila bolje korisničko iskustvo. Za više informacija o kolačićima i privatnosti osobnih podataka kliknite na ",link:"informacije/pravila-privatnosti",delay:2e3,expires:30,moreInfoLabel:"Više informacija",acceptBtnLabel:"Prihvaćam",advancedBtnLabel:"Uredi kolačiće",cookieTypesTitle:"Odaberite kolačiće",fixedCookieTypeLabel:"Obavezni",fixedCookieTypeDesc:"Ovo su neophodni kolačići koji su potrebni da stranica radi ispravno.",onAccept:function(){},uncheckBoxes:!1},n),c=a("cookieControl"),p=a("cookieControlPrefs");if(c&&p){var s=!0;"false"==c&&(s=!1),i(s,r.expires)}else{var l='<li><input type="checkbox" name="gdpr[]" value="necessary" checked="checked" disabled="disabled"> <label title="'+r.fixedCookieTypeDesc+'">'+r.fixedCookieTypeLabel+"</label></li>";e.each(r.cookieTypes,function(e,i){if(""!==i.type&&""!==i.value){var o="";!1!==i.description&&(o=' title="'+i.description+'"'),l+='<li><input type="checkbox" id="gdpr-cookietype-'+i.value+'" name="gdpr[]" value="'+i.value+'" data-auto="on"> <label for="gdpr-cookietype-'+i.value+'"'+o+">"+i.type+"</label></li>"}});var d='<div id="gdpr-cookie-message"><h4>'+r.title+"</h4><p>"+r.message+' <a href="'+r.link+'">'+r.moreInfoLabel+'</a><div id="gdpr-cookie-types" style="display:none;"><h5>'+r.cookieTypesTitle+"</h5><ul>"+l+'</ul></div><p><button id="gdpr-cookie-accept" type="button">'+r.acceptBtnLabel+'</button><button id="gdpr-cookie-advanced" type="button">'+r.advancedBtnLabel+"</button></p></div>";setTimeout(function(){e(t).append(d),e("#gdpr-cookie-message").hide().fadeIn("slow")},r.delay),e("body").on("click","#gdpr-cookie-accept",function(){i(!0,r.expires),e('input[name="gdpr[]"][data-auto="on"]').prop("checked",!0);var a=[];e.each(e('input[name="gdpr[]"]').serializeArray(),function(e,i){a.push(i.value)}),o("cookieControlPrefs",JSON.stringify(a),365),r.onAccept.call(this)}),e("body").on("click","#gdpr-cookie-advanced",function(){e('input[name="gdpr[]"]:not(:disabled)').attr("data-auto","off").prop("checked",!1),e("#gdpr-cookie-types").slideDown("fast",function(){e("#gdpr-cookie-advanced").prop("disabled",!0)})})}!0===r.uncheckBoxes&&e('input[type="checkbox"].ihavecookies').prop("checked",!1)},e.fn.ihavecookies.cookie=function(){var e=a("cookieControlPrefs");return JSON.parse(e)},e.fn.ihavecookies.preference=function(e){var i=a("cookieControl"),o=a("cookieControlPrefs");return o=JSON.parse(o),!1!==i&&(!1!==o&&-1!==o.indexOf(e))};var i=function(i,a){o("cookieControl",i,a),e("#gdpr-cookie-message").fadeOut("fast",function(){e(this).remove()})},o=function(e,i,o){var n=new Date;n.setTime(n.getTime()+24*o*60*60*1e3);var t="expires="+n.toUTCString();return document.cookie=e+"="+i+";"+t+";path=/",a(e)},a=function(e){for(var i=e+"=",o=decodeURIComponent(document.cookie).split(";"),a=0;a<o.length;a++){for(var n=o[a];" "==n.charAt(0);)n=n.substring(1);if(0===n.indexOf(i))return n.substring(i.length,n.length)}return!1}}(jQuery);