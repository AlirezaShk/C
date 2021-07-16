#ifndef MAINWINDOW_H
#define MAINWINDOW_H

#include <QMainWindow>
#include <capture.h>
#include <QDebug>
#include <QUdpSocket>

namespace Ui {
class MainWindow;
}

class MainWindow : public QMainWindow
{
    Q_OBJECT

public:
    explicit MainWindow(QWidget *parent = 0);
    ~MainWindow();
    capture cam;
    QByteArray robot_data;
    QHostAddress *GS_IP;
    QUdpSocket *send_img_socket;
    int GS_img_port;
    std::vector<uchar> enc_buff;
    std::vector<int> compression_params;
    QByteArray img_data;
private:
    Ui::MainWindow *ui;

public slots:
    void doSomeThing(Mat b);
};

#endif // MAINWINDOW_H
