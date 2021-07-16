#include "thermal_image.h"

thermal_image::thermal_image()
{
    my_thermal_im.Data.resize(80);
    mySocket->bind(8585);
    connect(mySocket, SIGNAL(readyRead()), this, SLOT(get_image()));
    for (int x = 0; x < 80; x++){
        for (int y = 0; y < THIMH; y++){
            my_thermal_im.Image.at<Vec3i>(Point(x, y))[0] = 166;
            my_thermal_im.Image.at<Vec3i>(Point(x, y))[1] = 166;
            my_thermal_im.Image.at<Vec3i>(Point(x, y))[2] = 166;
        }
    }
    connect(mySocket, SIGNAL(readyRead()),this, SLOT(get_image()));
}

void thermal_image::get_image(){
    QByteArray Temp;
    Temp.resize(164);
    mySocket->readDatagram(Temp.data(), mySocket->pendingDatagramSize(), lepton_address, &lepton_Port);
    my_thermal_im.ID = Temp.Data[0];
//    for (int i; i < 8; i++)
//    {
//        my_thermal_im.Data[i]
//    }
    QImage m;
    m.
}

Mat thermal_image::build_image(Th_Mat Th_Data)
{

}
