document.addEventListener('DOMContentLoaded', function(){
  if((e = document.querySelector("#form_error_message_frontend + div > div:last-child label")) !== null)
    e.classList.add('last'); // Аналог выборки и присвоения класса
  // Если элементов будет много
  // let checkingSolution = document.getElementById('checkingSolution');
  //
  // var myCodeMirror = CodeMirror.fromTextArea(checkingSolution, {
  //   lineNumbers: true,               // показывать номера строк
  //   matchBrackets: true,             // подсвечивать парные скобки
  //   mode: checkingSolution.getAttribute('mode') ?? 'javascript', // стиль подсветки
  //   indentUnit: 4                    // размер табуляции
  // });

  let sampleTest = document.getElementById('sampleTest');

  CodeMirror.fromTextArea(sampleTest, {
    lineNumbers: true,
    matchBrackets: true,
    mode: sampleTest.getAttribute('mode') ?? 'javascript',
    indentUnit: 4
  });


})
