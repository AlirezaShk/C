FROM python:3.9.7

WORKDIR /usr/src/app
COPY requirements.txt .
RUN pip install -r requirements.txt
COPY . .

# RUN touch /etc/apt/sources.list.d/google-chrome.list
# RUN echo "deb [arch=amd64] http://dl.google.com/linux/chrome/deb/ stable main" > /etc/apt/sources.list.d/google-chrome.list

# RUN wget -O- https://dl.google.com/linux/linux_signing_key.pub |gpg --dearmor > /etc/apt/trusted.gpg.d/google.gpg

RUN apt-get update
RUN apt-get install -y libglib2.0-0 \
    libnss3 \
    libgconf-2-4 \
    libfontconfig1

# RUN apt install google-chrome-stable -y


EXPOSE $EXPOSE_PORT
CMD "python manage.py runserver 0.0.0.0:8000"