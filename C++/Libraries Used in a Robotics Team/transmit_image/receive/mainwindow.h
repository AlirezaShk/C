#ifndef MAINWINDOW_H
#define MAINWINDOW_H

#include <QMainWindow>
#include <QDebug>
#include <QUdpSocket>
#include <opencv2/opencv.hpp>

using namespace cv;

namespace Ui {
class MainWindow;
}

class MainWindow : public QMainWindow
{
    Q_OBJECT

public:
    explicit MainWindow(QWidget *parent = 0);
    ~MainWindow();
    QUdpSocket *rec_img_socket;
    int data_port;
    int height;
    int camID;
    float batt;
    QByteArray datagram;
    Mat dec_mat;
private:
    Ui::MainWindow *ui;

public slots:
    void receive_data();
};

#endif // MAINWINDOW_H
