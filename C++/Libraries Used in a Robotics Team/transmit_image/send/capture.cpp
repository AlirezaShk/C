#include "capture.h"

capture::capture(QObject *parent) : QObject(parent)
{
    cap.open(0);
    cap >> frame;
    connect(&timer,SIGNAL(timeout()),this,SLOT(timeOut()));
    timer.start(2);
}

void capture::timeOut()
{

    double exec_time = (double)getTickCount();
     cap >> frame;
    exec_time = ((double)getTickCount() - exec_time)*1000./getTickFrequency();
    qDebug() << exec_time;
    emit imageReady(frame);
}

