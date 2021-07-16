#include "mainwindow.h"
#include "ui_mainwindow.h"

MainWindow::MainWindow(QWidget *parent) :
    QMainWindow(parent),
    ui(new Ui::MainWindow)
{
    ui->setupUi(this);

    data_port=1234;
    rec_img_socket = new QUdpSocket(this);
    rec_img_socket->bind(data_port);
    connect(rec_img_socket,SIGNAL(readyRead()),this,SLOT(receive_data()));
}

MainWindow::~MainWindow()
{
    delete ui;
}


void MainWindow::receive_data()
{
    //qDebug() << "rec";

    datagram.resize(rec_img_socket->pendingDatagramSize());
    rec_img_socket->readDatagram(datagram.data(), datagram.size());
    rec_img_socket->flush();

    std::vector<uchar> bufferToCompress(datagram.begin(), datagram.end());
    dec_mat=imdecode(bufferToCompress,CV_LOAD_IMAGE_COLOR); //be un var begu color befreste

    datagram.clear();

    imshow("decoded img",dec_mat);

}


