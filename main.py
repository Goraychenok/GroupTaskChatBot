import telebot
import requests

token = '5985398035:AAHQTpy4XWOkYfUSzTL-v2KMDrY13P41t-Y'

bot = telebot.TeleBot(token)
@bot.message_handler(commands=['start'])
def start(message):
    bot.send_message(message.chat.id, text='Подключение к bitrix24...')

    try:
        r = requests.get('https://aerokod.bitrix24.ru/rest/82/urkeup9m3sxbllxo/sonet_group.get')
    except:
        bot.send_message(message.chat.id, text='Извините, ошибка подключения к bitrix24, попробуйте позже')
    else: 
        items = r.json()['result']
        keyboard = telebot.types.InlineKeyboardMarkup()
        
        for element in items:
            keyboard.add(telebot.types.InlineKeyboardButton(text=element['NAME'], callback_data=element['ID']))

        bot.send_message(message.chat.id, text='Выберите проект', reply_markup=keyboard)
       
@bot.message_handler(commands=['help'])
def help(message):
    bot.send_message(message.chat.id, text=f'Ваш id чата: "{message.chat.id}"')

@bot.callback_query_handler(func=lambda call: True)
def inline_kb(call):
    payload = {'chat_id': call.message.chat.id, 'group_id': call.data}
    bot.send_message(call.message.chat.id, text='Отправка данных на сервер')
    try:
        setParam = requests.post('https://crm-integration.aerokod.ru/bot/setParametr.php', data=payload)
    except:
        bot.edit_message_text(chat_id=call.message.chat.id, message_id=call.message.message_id, text='Извините, данные не были отправлены, пожалуйста попробуйте позже')
    else:
        bot.edit_message_text(chat_id=call.message.chat.id, message_id=call.message.message_id, text=f'Вы выбрали проект с id: {call.data} \nТеперь сюда будут приходить задачи по этому проекту')

bot.polling(non_stop=True)