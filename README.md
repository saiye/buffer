### buffer 微型框架

```text
执行命令生成ssh 密钥对可以指定生成的文件名称
ssh-keygen -t rsa -C '714433615@qq.com' 

#本地服务器添加ssh 缓存
ssh-add id_ras

-D
	删除 ssh-agent 中的所有密钥。
-d
	从 ssh-agent 中的删除密钥。
-e <pkcs11>
	删除 PKCS#11 共享库 pkcs11 提供的钥匙。
-s <pkcs11>
	添加 PKCS#11 共享库 pkcs1 提供的钥匙
-L
	列出 ssh-agent(1) 中的公钥。
-l
	列出 ssh-agent(1) 中当前所代表的所有身份的指纹。
-t <life>
	对加载的密钥设置超时时间，超时 ssh-agent(1) 将自动卸载密钥。
-X
	对 ssh-agent 进行解锁。
-x
	对 ssh-agent 使用密码进行加锁。
	
#ssh-copy-id 拷贝公钥到远程服务器	ssh-copy-id [-i [identity_file]] [user@]machine

demo:
ssh-copy-id -i	kfcp.pub www@kfcp.cn

```


