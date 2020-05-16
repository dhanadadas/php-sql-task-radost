Варианты через крон
1. т.к. крон не запускается чаще 1 минуты можно такой финт провернуть, добавив 3 задания.
* * * * * root /home/update.php
* * * * * root ( sleep 20 ; /home/update.php )
* * * * * root ( sleep 40 ; /home/update.php )
В итоге за минуту исполнится 3 раза. 

2. Можно написать простой скрипт и его вызывать в крон 1 раз.
for ($i=0;$i<=2;$i++){
	exec("php /home/update.php > /dev/null 2>/dev/null &");
	sleep (20);
}

* * * * * root /home/script.php


Другие варианты без использования cron
Иногда крон ведет себя не так, как хотелось бы в точности. Тогда можно использовать следующие варианты:
1. Использовать supervisord для контроля процесса update.php
В конец update.php
sleep (20);

supervisord замечательно выполняет свою функцию. Таким образом скрипт будет выполнятся в любом случае каждые 20 секунд, без использования крона. Для такой простой задачи может быть не самое оптимальное решение, но в реальных фоновых обработках незаменим. Особенно в многопоточных обработках с использованием очереди процессов.
Конфиг supervisor'а:
[program:update]
command=php /home/update.php learning:update --action=worker --execute=1
process_name=%(program_name)s_%(process_num)02d
numprocs=1
directory=/home/
autostart=true
autorestart=true

2. Можно в конце update.php 
написать рекурсивный вызов
...
sleep (20);
exec("php /home/update.php > /dev/null 2>/dev/null &");
Не самое надежное решение.

3. Написать демон, который запускает скрипт каждые 20 сек. Добавить в автозагрузки.
Самый простой так:
nohup watch --interval=20 /home/update.php > /dev/null