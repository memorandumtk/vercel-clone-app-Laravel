kosuke@Kousuke:~/laravel/next-clone-laravel/storage/dep$ cd /etc/systemd/
kosuke@Kousuke:/etc/systemd$ ll
total 56
drwxr-xr-x  5 root root 4096 Dec 29 19:39 ./
drwxr-xr-x 98 root root 4096 Mar  4 15:31 ../
-rw-r--r--  1 root root 1282 Apr  7  2022 journald.conf
-rw-r--r--  1 root root 1374 Apr  7  2022 logind.conf
drwxr-xr-x  2 root root 4096 Apr  7  2022 network/
-rw-r--r--  1 root root  846 Mar 11  2022 networkd.conf
-rw-r--r--  1 root root  670 Mar 11  2022 pstore.conf
-rw-r--r--  1 root root 1406 Apr  7  2022 resolved.conf
-rw-r--r--  1 root root  931 Mar 11  2022 sleep.conf
drwxr-xr-x 12 root root 4096 Mar  4 15:31 system/
-rw-r--r--  1 root root 1993 Apr  7  2022 system.conf
-rw-r--r--  1 root root  748 Apr  7  2022 timesyncd.conf
drwxr-xr-x  4 root root 4096 Sep  3  2023 user/
-rw-r--r--  1 root root 1394 Apr  7  2022 user.conf
kosuke@Kousuke:/etc/systemd$ mkdir resolved.conf.d
mkdir: cannot create directory ‘resolved.conf.d’: Permission denied
kosuke@Kousuke:/etc/systemd$
kosuke@Kousuke:/etc/systemd$ sudo mkdir resolved.conf.d
kosuke@Kousuke:/etc/systemd$
kosuke@Kousuke:/etc/systemd$ ll
total 60
drwxr-xr-x  6 root root 4096 Mar  4 15:42 ./
drwxr-xr-x 98 root root 4096 Mar  4 15:31 ../
-rw-r--r--  1 root root 1282 Apr  7  2022 journald.conf
-rw-r--r--  1 root root 1374 Apr  7  2022 logind.conf
drwxr-xr-x  2 root root 4096 Apr  7  2022 network/
-rw-r--r--  1 root root  846 Mar 11  2022 networkd.conf
-rw-r--r--  1 root root  670 Mar 11  2022 pstore.conf
-rw-r--r--  1 root root 1406 Apr  7  2022 resolved.conf
drwxr-xr-x  2 root root 4096 Mar  4 15:42 resolved.conf.d/
-rw-r--r--  1 root root  931 Mar 11  2022 sleep.conf
drwxr-xr-x 12 root root 4096 Mar  4 15:31 system/
-rw-r--r--  1 root root 1993 Apr  7  2022 system.conf
-rw-r--r--  1 root root  748 Apr  7  2022 timesyncd.conf
drwxr-xr-x  4 root root 4096 Sep  3  2023 user/
-rw-r--r--  1 root root 1394 Apr  7  2022 user.conf
kosuke@Kousuke:/etc/systemd$
kosuke@Kousuke:/etc/systemd$
kosuke@Kousuke:/etc/systemd$ cd resolved.conf.d/
kosuke@Kousuke:/etc/systemd/resolved.conf.d$
kosuke@Kousuke:/etc/systemd/resolved.conf.d$ vim vercel-local.com.conf

[1]+  Stopped                 vim vercel-local.com.conf
kosuke@Kousuke:/etc/systemd/resolved.conf.d$ vim vercel-local.com.conf
kosuke@Kousuke:/etc/systemd/resolved.conf.d$
kosuke@Kousuke:/etc/systemd/resolved.conf.d$ sudo vim vercel-local.com.conf

[2]+  Stopped                 sudo vim vercel-local.com.conf
kosuke@Kousuke:/etc/systemd/resolved.conf.d$ sudo vim vercel-local.com.conf
kosuke@Kousuke:/etc/systemd/resolved.conf.d$ sudo vim vercel-local.com.conf
kosuke@Kousuke:/etc/systemd/resolved.conf.d$
kosuke@Kousuke:/etc/systemd/resolved.conf.d$
kosuke@Kousuke:/etc/systemd/resolved.conf.d$ cat vercel-local.com.conf
[Resolve]
DNS=127.0.0.1
Domains=~vercel-local.com
