<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Сервис сокращения ссылок</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">

    <script type="text/javascript">
        async function getShortLink() {
            let csrf = document.getElementsByName('csrf-token');
            let link = document.getElementById('link').value;
            if(validURL(link) === false) {
                alert('Вы ввели не правильный URL');
                return false;
            }

            let body = {
                link: link //добавляем ссылку, которую необходимо сократить
            };

            let response = await fetch('/short', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrf[0].content //необходимо для CSRF Protection
                },
                body: JSON.stringify(body)
            });

            let result = await response.json();
            addResult(result);
        }

        function addResult(result = false) {
            if(result) {
                let div_short_link = document.getElementById('div_short_link');
                //ищем старые ссылки, если они есть, удаляем их
                let elem_a_original_link = document.getElementById("a_original_link");
                let elem_a_short_link  = document.getElementById("a_short_link");
                if(elem_a_original_link){
                    elem_a_original_link.parentNode.removeChild(elem_a_original_link);
                }
                if(elem_a_short_link){
                    elem_a_short_link.parentNode.removeChild(elem_a_short_link);
                }
                //создаем ссылки и добавляем их в div блоки
                let a_original_link = '<a id="a_original_link" href="'+result.original_link+'" target="_blank">'+result.original_link+'</a>';
                let a_short_link = '<a id="a_short_link" href="'+result.short_link+'" target="_blank">'+result.short_link+'</a>';
                document.getElementById('original_link').innerHTML += a_original_link;
                document.getElementById('short_link').innerHTML += a_short_link;
                div_short_link.style.visibility='visible'; //делаем div видимым
            }
        }

        function validURL(link) {
            //Функция для проверки валидности URL, поддерживает кириллицу
            let RegExp = /^((ftp|http|https):\/\/)?(www\.)?([A-Za-zА-Яа-я0-9]{1}[A-Za-zА-Яа-я0-9\-]*\.?)*\.{1}[A-Za-zА-Яа-я0-9-]{2,8}(\/([\w#!:.?+=&%@!\-\/])*)?/;

            if(RegExp.test(link)){
                return true
            } else {
                return false
            }
        }

    </script>

</head>
<body>
    <div class="container">
        <h1>Сервис сокращения ссылок</h1>
        <br>
        <div class="mb-3">
            <label for="link" class="form-label">Оригинальная ссылка</label>
            <input name="link" type="text" class="form-control" id="link" aria-describedby="linkHelp">
            <div id="linkHelp" class="form-text">Введите ссылку, которую хотите сократить</div>
        </div>
        <button class="btn btn-primary" onclick="getShortLink()">Сократить</button>




        <div class="mb-3" id="div_short_link" style="visibility: hidden;">
            <hr>
            <div class="row">
                <div class="col-lg-4">
                    <h4>Оригинальная</h4>
                </div>
                <div class="col-lg-4">
                    <h4>Сокращенная</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4" id="original_link">

                </div>
                <div class="col-lg-4" id="short_link">

                </div>
            </div>
        </div>
    </div>


</body>
</html>
