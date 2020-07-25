<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

</head>
<body>

    <main id="app">

        <ul>
            <li style="display: inline-block; margin-left: 5px;" v-for="article in articles">
                <img :src="article.img" alt="">
                <h1>@{{ article.title }}</h1>
                <p>@{{ article.body }}</p>
                <span>@{{ article.price }}</span>
            </li>
        </ul>

    </main>

    <script SRC="https://unpkg.com/vue@2.1.10/dist/vue.js"></script>
    <script type="text/javascript">
        new Vue({
            el: '#app',
            data: {
                articles: [
                    {
                        title: "P1",
                        body: "lorem ipsum",
                        price: 25000,
                        img: 'https://picsum.photos/200/300'
                    },
                    {
                        title: "P2",
                        body: "lorem ipsum",
                        price: 30000,
                        img: 'https://picsum.photos/200/300'
                    },
                    {
                        title: "P3",
                        body: "lorem ipsum",
                        price: 35000,
                        img: 'https://picsum.photos/200/300'
                    }
                ]
            }
        });
    </script>

</body>
</html>
