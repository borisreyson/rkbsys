var form = document.getElementById('login');
var buttonE1 = document.getElementById('e1');

buttonE1.addEventListener('click', function () {
  form.classList.add('error_1');
  setTimeout(function () {
    form.classList.remove('error_1');
  }, 3000);
});
function error_1(){
	form.classList.add('error_1');
  setTimeout(function () {
    form.classList.remove('error_1');
  }, 3000);
}
function error_2(){
	form.classList.add('error_2');
  setTimeout(function () {
    form.classList.remove('error_2');
  }, 3000);
}
function error_3(){
	form.classList.add('error_3');
  setTimeout(function () {
    form.classList.remove('error_3');
  }, 3000);
}