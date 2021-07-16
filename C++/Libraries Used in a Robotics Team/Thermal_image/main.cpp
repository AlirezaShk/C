#include <QCoreApplication>
#include "thermal_image.h"
int main(int argc, char *argv[])
{
    QCoreApplication a(argc, argv);
    thermal_image im;
    Mat m;
    while(1){
        imshow("Thermal Image", im.my_thermal_im.Image);
        usleep(20);
    }
    return a.exec();
}
