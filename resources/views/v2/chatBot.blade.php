@php
$host="http://104.197.37.185:5500/";
$socket="http://104.197.37.185:5005/";
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"
  />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/9245f43cf7.js" crossorigin="anonymous"></script>
    <title>ChatBot</title>

       <style>
        .rw-conversation-container .rw-header{

            height:70px !important;
        }
        .rw-open-launcher__container:hover{
          animation: none !important;
        }
       </style>
       <script>
           console.log('chatbot',multilistType);
       </script>
</head>
<body>
</body>
  <script type="text/javascript"  async src="{{$host}}/script.js" defer></script>
  <link rel="stylesheet" href="{{$host}}/style.css">
    <body>
        <script>(function () {
            let e = document.createElement("script"),
              t = document.head || document.getElementsByTagName("head")[0];
            (e.src =
              "{{$host}}/index.js"),
              // Replace 1.x.x with the version that you want
              (e.async = !0),
              (e.onload = () => {
                window.WebChat.default(
                  {
                    initPayload:'/lang',
                    customData: { language: "fr" },
                    socketUrl: "{{$socket}}",
                    // add other props here
                  },
                  null
                );
              }),
              t.insertBefore(e, t.firstChild);
          })();
          </script>
          <script>
          localStorage.setItem("globFlag","true")
          let msg = null;
          let container = document.querySelector('.rw-sender');
           function fun(){
              if (localStorage.getItem('globFlag') == "true"){
                if(container){
                  document.querySelector('.rw-sender').style.display='block'
                document.querySelector('.rw-sender').style.display='none'
                localStorage.setItem("globFlag","false")
                }
              }
              var _tags = document.getElementsByClassName('rw-group-message rw-from-response');
              if (_tags.length>1.5){
                msg = new TextEncoder().encode(_tags[_tags.length-1].innerText).join('')
                if(msg){
                  if (msg.search(localStorage.getItem('en01'))!=-1 || msg.search(localStorage.getItem('fr01'))!=-1 || msg.search(localStorage.getItem('ar01'))!=-1){
                  document.querySelector('.rw-sender').style.display='flex'
                  document.querySelector('.rw-sender').style.display='flex'               }
                }
                }
                if(msg){
                  if (msg.search(localStorage.getItem('en02'))!=-1 || msg.search(localStorage.getItem('fr02'))!=-1 || msg.search(localStorage.getItem('ar02'))!=-1){
                    document.querySelector('.rw-sender').style.display='block'
                    document.querySelector('.rw-sender').style.display='none'
                    localStorage.setItem("globFlag","true")
                    clearInterval(refreshIntervalId);
                }
                }
              };
              var refreshIntervalId = setInterval(fun, 10);


                setInterval(() => {
                const cBot = document.querySelector('.rw-open-launcher__container')
                if(cBot){
                    cBot.classList.add('animate__animated')
                    cBot.classList.add('animate__infinite')
                    cBot.classList.add('animate__bounce')
                    setInterval(() => {
                      cBot.classList.toggle('animate__animated')
                    cBot.classList.toggle('animate__infinite')
                    cBot.classList.toggle('animate__bounce')
                    }, 7000);
                }
                }, 2000);

            </script>
    </body>
</html>


