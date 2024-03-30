import asyncio
import datetime
import random
import boto3, secrets
import time
import discord
from bs4 import BeautifulSoup
import requests
import re
import os
from urllib.parse import urlparse
from webserver import keep_alive
from dotenv import load_dotenv
load_dotenv()  # Load environment variables from a .env file

TOKEN = os.getenv('DISCORD_TOKEN')

intents = discord.Intents.all()  # Ensure the bot intents include messages

client = discord.Client(intents=intents)
running = False

# Check if the guild is allowed
allowed_channel_ids = [1164624409068306493, 1062673831896027167, 1163164887090995363,1150483600106590268]

@client.event
async def on_ready():
    global running
    if not running:
        print(f'Logged in as {client.user.name} ({client.user.id})')
        print('\033[92m' + '---------------------BOT ONLINE-----------------------' + '\033[0m')
        running = True # is check is not running for multiple times
    else:
        print("on_ready event triggered again, but running is already True")
        print('-----------------------------------------------------------')

def egg_scrap(url, identifier):
  try:
    print(f"Path: {url}")
    likeapi = "https://nx.aba.vg/nxcode/eggapinxproapi.php?url=" + str(url)
    response = requests.get(likeapi)
    soup = BeautifulSoup(response.content, "html.parser")
    file_answer = f"answers_{identifier}.html"

    with open(file_answer, 'a', encoding='utf-8') as f:
      f.write(soup.prettify())

    return file_answer
  except Exception as e:
    print(e)
    raise e


def bartleby_scrap(url, identifier):
  try:
    print(f"Path: {url}")
    likeapi = "http://nx.aba.vg/bartleby/index.php?url=" + str(url)
    response = requests.get(likeapi)
    soup = BeautifulSoup(response.content, "html.parser")
    file_answer = f"answers_{identifier}.html"

    with open(file_answer, 'a', encoding='utf-8') as f:
      f.write(soup.prettify())

    return file_answer
  except Exception as e:
    print(e)
    raise e


#aws to store s3 files
def generate_unique_token(existing_tokens):
  while True:
    gen_token = secrets.token_hex(16)
    if gen_token not in existing_tokens:
      return gen_token


def upload_to_s3(file_answer):
  s3 = boto3.client(
      's3',
      region_name='us-east-1',
      aws_access_key_id='Yourkey_id',
      aws_secret_access_key='your access key',
      config=boto3.session.Config(signature_version='s3v4'))

  bucket_name = 'supernova558866'
  s3.upload_file(file_answer, 'supernova558866', file_answer)
  link = s3.generate_presigned_url('get_object',
                                   Params={
                                       'Bucket': 'AWSBucketName',
                                       'Key': file_answer
                                   },
                                   ExpiresIn=100000)
  print(link)
  existing_tokens = set()  # Assume you're keeping track of generated tokens
  GenToken = generate_unique_token(existing_tokens)
  s3.upload_file(file_answer,
                 bucket_name,
                 f'{GenToken}.html',
                 ExtraArgs={'ContentType': 'text/html'})
  url3 = s3.generate_presigned_url(
      ClientMethod='get_object',
      Params={
          'Bucket': bucket_name,
          'Key': f'{GenToken}.html'
      },
      ExpiresIn=86400  # 1 Day
  )
  return url3


sudos = [1164267844905742387]
async def send_message_answer(message, url, url3, username):
  gifs = [
      'https://media1.giphy.com/media/jnhXd7KT8UTk5WIgiV/giphy.gif',
      'https://media1.giphy.com/media/v1.Y2lkPTc5MGI3NjExa3ZmZTFzbnF3OXNyeGd4c3ZtdmczOHBjMG1nejRrNmZ6ZjR5M2RybyZlcD12MV9pbnRlcm5hbF9naWZfYnlfaWQmY3Q9Zw/StKiS6x698JAl9d6cx/giphy.gif',
      'https://media0.giphy.com/media/fBEMsUeGHdpsClFsxM/giphy.gif',
      'https://media4.giphy.com/media/gp3aS4doWX6OYSuOI8/giphy.gif'
  ]

  embed_title1 = "Link Received!"
  embed_description1 = "Your document will be sent in a minute. {}"

  embed1 = discord.Embed(title=embed_title1,
                         description=embed_description1.format(username),
                         color=0xFF5733)
  embed1.set_thumbnail(url=random.choice(gifs))
  embed1.add_field(name="User Link:",
                   value=f"[{username}]({url})",
                   inline=False)
  embed1.set_footer(text="Contact Owner for any issues")
  embed1.timestamp = datetime.datetime.utcnow()
  await message.channel.send(embed=embed1)

  embed_title2 = "Your answer is here!"
  embed_description2 = "Open the link to view the answer.. {}"

  embed2 = discord.Embed(title=embed_title2,
                         description=embed_description2.format(username))
  embed2.set_thumbnail(url=random.choice(gifs))
  embed2.add_field(name="Answer Link:",
                   value=f"[{username}]({url3})",
                   inline=False)
  embed2.set_footer(text="Contact Owner for any issues")
  embed2.timestamp = datetime.datetime.utcnow()
  await message.channel.send(embed=embed2)

  embed_title3 = "Answer Sent!"
  embed_description3 = "Raise a ticket in case of issues <@1038087486808793219>. Find us back here if we disappear: https://solo.to/cheggcoursehero"

  embed3 = discord.Embed(title=embed_title3,
                         description=embed_description3,
                         color=0x009999)
  embed3.set_thumbnail(url=random.choice(gifs))
  embed3.set_footer(text="Contact Owner for any issues")
  embed3.timestamp = datetime.datetime.utcnow()
  await message.author.send(embed=embed3)

@client.event
async def on_message(message):
  print(f"Text BOT: {message.content}")
  # Ignore messages sent by the bot itself
  if message.author == client.user:
    return

  if message.channel.id in allowed_channel_ids:
    if message.content.startswith("https://www.chegg.com/"):
      username = message.author.name
      channel = message.channel.id
      url_list = re.findall(r'(https://(?:www.)?chegg.com/homework-help/\S+)',
                            message.content)
      print(f'Running {url_list}')
      for url in url_list:
        try:
          try:
            print(f"Path:{url}")
            existing_tokens = set(
            )  # Assume you're keeping track of generated tokens
            identifier = generate_unique_token(existing_tokens)
            file_answer = egg_scrap(url, identifier)
          except:
            pattern = r'q(\d+)'
            match = re.search(pattern, url)
            '''
            identifier = match.group(1)
            '''
            existing_tokens = set(
            )  # Assume you're keeping track of generated tokens
            identifier = generate_unique_token(existing_tokens)
            print(f"Path:{identifier}")
            file_answer = egg_scrap(url, identifier)
        except Exception as e:
          print(e)
          await message.channel.send(f"Error: {e}")
      # send the answers.html to user
      if os.path.exists(file_answer):
        url3 = upload_to_s3(file_answer)
        await send_message_answer(message, url, url3, username)
        os.remove(file_answer)

    #bartleby
    elif message.content.startswith("https://www.bartleby.com/"):
      username = message.author.name
      channel = message.channel.id
      url_list = re.findall(r'(https://(?:www.)?bartleby.com/\S+)',
                            message.content)
      print(f'Running {url_list}')
      for url in url_list:
        try:
          existing_tokens = set(
          )  # Assume you're keeping track of generated tokens
          identifier = generate_unique_token(existing_tokens)
          file_answer = bartleby_scrap(url, identifier)
        except Exception as e:
          print(e)
          await message.channel.send(f"Error: {e}")
      # send the answers.html to user
      if os.path.exists(file_answer):
        url3 = upload_to_s3(file_answer)
        await send_message_answer(message, url, url3, username)
        os.remove(file_answer)
        
    #scribd
    elif message.content.startswith("https://www.scribd.com/"):
      username = message.author.name
      channel = message.channel.id
      url_list = re.findall(r'(https://(?:www.)?scribd.com/\S+)',
                            message.content)
      print(f'Running {url_list}')
      for url in url_list:
        try:
          identifier = 'scribd'
          match = re.search(r'/(\d+)/', url)
          if match:
            document_number = match.group(1)
            print(document_number)
            file_answer = f"https://www.scribd.com/embeds/{document_number}/content"
            url3 = file_answer
            await send_message_answer(message, url, url3, username)
          else:
            print("Document number not found.")
            await message.channel.send(f"Document not found: {url}")
        except Exception as e:
          print(e)
          await message.channel.send(f"Error: {e}")
    #status      
    elif '/status' in message.content:
      urlstatus = 'https://nx.aba.vg/nxcode/check.php'
      response = requests.get(urlstatus)
      if response.status_code == 200:
        datastau = response.json()
        account = len(datastau)
        for index, datastatus in enumerate(datastau):
          if datastatus in ['RELEASED', 'OK']:
            await message.channel.send(f"Account {index + 1}/{account}: Active ✅")
          elif datastatus in ['BLOCKED', 'WARNED']:
            await message.channel.send(f"Account {index + 1}/{account}: Blocked 🚫")
      else:
          await message.channel.send("Failed to fetch status.")
  #else:
  #if isinstance(message.channel, discord.DMChannel):
  #await message.reply("Nice Try!")

keep_alive()
# Use your actual token here, and keep it secret!
client.run(TOKEN)
