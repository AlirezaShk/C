#ifndef CAPTURE_H
#define CAPTURE_H

#include <QObject>
#include <opencv2/opencv.hpp>
#include <QTimer>
#include <QDebug>

using namespace cv;

class capture : public QObject
{
    Q_OBJECT
public:
    explicit capture(QObject *parent = 0);
    Mat frame;
    VideoCapture cap;
    QTimer timer;
signals:
    void imageReady(Mat a);

public slots:
    void timeOut();
};

#endif // CAPTURE_H
