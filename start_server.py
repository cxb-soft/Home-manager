#coding = utf-8

import socket
import smtplib
from email.mime.text import MIMEText
from email.header import Header
import pymysql
import datetime
import time

host = {
        "ip":"The local ip",
        "port":9897,
        "mysql_address":"Your e-mail address",
        "mysql_username":"Database Username",
        "mysql_password":"Database Password",
        "mysql_dbname":"Database Name"
} 

db = pymysql.connect(host['mysql_address'],host["mysql_username"],host['mysql_password'],host['mysql_dbname'])
print("Connect ok!")
cursor = db.cursor()

def get_time():
    now = datetime.datetime.now()
    time_now = now.strftime("%Y-%m-%d %H:%M:%S")
    return time_now

def send_mail(content,subject,mail_receiver):
    mail_host="Your e-mail's server"  
    mail_user="Your e-mail address"   
    mail_pass="E-mail password"   
    
    
    sender = 'As same as $mail_user'
    receivers = mail_receiver  
    touser = "Home Master"

    message = MIMEText(content, 'plain', 'utf-8')
    message['From'] = Header(sender, 'utf-8')
    message['To'] =  Header(touser, 'utf-8')
    message['Subject'] = Header(subject, 'utf-8')
    print("OKOK")
    
    smtpObj = smtplib.SMTP() 
    print("asdsa")
    smtpObj.connect(mail_host, 25)    
    print("okokoko")
    smtpObj.login(mail_user,mail_pass)  
    print("okokokokok")
    smtpObj.sendmail(sender, receivers, message.as_string())
    #print("邮件发送成功")
def process(cmd):
    cmd = cmd.split(":")
    Ma_name = cmd[0]
    info1 = cmd[1] 
    commd = "select * from users where Ma_name='%s'"%Ma_name
    result = cursor.execute(commd)
    result = cursor.fetchall()
    E_mail = []
    for row in result:
        E_mail.append(row[3])
    print("Ma_name:%s \n"%Ma_name)
    print("cmd:%s \n"%info1)
    time_now = get_time()
    if("There is someone outside the door" in info1):
        print("Process")
        send_mail("有人在家门外","警告",E_mail)
        print("OK")
        message = "有人在家门外，蜂鸣器响起"
        commd = "insert into information values('%s','%s','%s')"%(Ma_name,message,time_now)
        cursor.execute(commd)
        print("Finish")
    elif("The door is open" in info1):
        print("Process")
        send_mail("门帮您打开了","开门",E_mail)
        print("OK")
        message = "检测到您靠近门，门为您打开了"
        commd = "insert into information values('%s','%s','%s')"%(Ma_name,message,time_now)
        cursor.execute(commd)
        print("Finish")
    elif("Fan open" in info1):
        print("Process")
        send_mail("温度高于35摄氏度，风扇开启","风扇开启",E_mail)
        print("OK")
        message = "风扇开启"
        commd = "insert into information values('%s','%s','%s')"%(Ma_name,message,time_now)
        cursor.execute(commd)
        print("Finish")
    db.commit()
 #Build a socket connection
s = socket.socket()

s.bind((host['ip'],host['port']))
s.listen(5)
print("Server start")
time1=[0,]
while(True):
    time1.append(time.time())
    ip,addr=s.accept()
    print("A connetion has been maded!\n")
    #ip.send(b"hello")
    result = str(ip.recv(1024))[2:-1]
    result = result.split(r"\r\n\r\n")[-1]
    print(result+"\n")
        if(time1[-1] - time1[-2] <= 5):
            time.sleep(5)
        process(result)
    except:
        send_mail("邮件发送失败","Failed!!!")
    ip.close()
