####测试


```text
…or create a new repository on the command line
echo "# buffer" >> README.md
git init
git add README.md
git commit -m "first commit"
git remote add origin git@github.com:saiye/buffer.git
git push -u origin master

…or push an existing repository from the command line
git remote add origin git@github.com:saiye/buffer.git
git push -u origin master

…or import code from another repository
You can initialize this repository with code from a Subversion, Mercurial, or TFS project.


ssh-keygen -t rsa -b 4096


查看git用户名
git config user.name
git config user.email

git config --global user.name  'buffer-linux';
git config --global user.email "714433615@qq.com"


git config --global user.name  'buffer-pc';
git config --global user.email "1324108818@qq.com"

windows的git bash，输入ssh-keygen -t rsa -C “邮箱”，生成ssh私钥和公钥
ssh-keygen -t rsa -C "1324108818@qq.com"
此时，C:\Users\用户名.ssh 下会多出两个文件 id_rsa 和 id_rsa.pub。id_rsa 是私钥，id_rsa.pub是公钥。
```