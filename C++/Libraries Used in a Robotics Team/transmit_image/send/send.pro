#-------------------------------------------------
#
# Project created by QtCreator 2017-04-18T22:30:31
#
#-------------------------------------------------

QT       += core gui network

greaterThan(QT_MAJOR_VERSION, 4): QT += widgets

TARGET = send
TEMPLATE = app


SOURCES += main.cpp\
        mainwindow.cpp \
    capture.cpp

HEADERS  += mainwindow.h \
    capture.h

FORMS    += mainwindow.ui
LIBS += `pkg-config opencv --libs`
