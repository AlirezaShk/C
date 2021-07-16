#ifndef THERMAL_IMAGE_H
#define THERMAL_IMAGE_H
#include <QHostAddress>
#include <QUdpSocket>
#include <opencv/cv.h>
#include <opencv2/opencv.hpp>
#include <QByteArray>
#include <unistd.h>
#include <QIm
#define THIMH 60
using namespace cv;
using namespace std;

struct Th_Mat{
    QByteArray Data;
    short int ID;
    int CRC;
    Mat Image;
};

class thermal_image : public QObject
{
public:
    Th_Mat my_thermal_im;
    QUdpSocket* mySocket = new QUdpSocket;
    short unsigned lepton_Port = 7575;
    QHostAddress* lepton_address = new QHostAddress("192.168.1.105");
    short unsigned myPort = 8585;
    QHostAddress* my_address = new QHostAddress("192.168.1.125");

    thermal_image();
    Mat build_image(Th_Mat Th_Data);

public slots:
    void get_image();
};

#endif // THERMAL_IMAGE_H
