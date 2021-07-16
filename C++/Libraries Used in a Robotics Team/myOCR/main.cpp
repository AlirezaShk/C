#include <QCoreApplication>
#include "header.h"

IplImage* imagen;
int red,green,blue;
IplImage* screenBuffer;
int drawing;
int r,last_x, last_y;



/*************************
* Mouse CallBack
* event:
*	#define CV_EVENT_MOUSEMOVE      0
*	#define CV_EVENT_LBUTTONDOWN    1
*	#define CV_EVENT_RBUTTONDOWN    2
*	#define CV_EVENT_MBUTTONDOWN    3
*	#define CV_EVENT_LBUTTONUP      4
*	#define CV_EVENT_RBUTTONUP      5
*	#define CV_EVENT_MBUTTONUP      6
*	#define CV_EVENT_LBUTTONDBLCLK  7
*	#define CV_EVENT_RBUTTONDBLCLK  8
*	#define CV_EVENT_MBUTTONDBLCLK  9
*
* x, y: mouse position
*
* flag:
*	#define CV_EVENT_FLAG_LBUTTON   1
*	#define CV_EVENT_FLAG_RBUTTON   2
*	#define CV_EVENT_FLAG_MBUTTON   4
*	#define CV_EVENT_FLAG_CTRLKEY   8
*	#define CV_EVENT_FLAG_SHIFTKEY  16
*	#define CV_EVENT_FLAG_ALTKEY    32
*
**************************/



int main( int argc, char** argv )
{
    printf( "Basic OCR by David Millan Escriva | Damiles\n"
        "Hot keys: \n"
    "\tr - reset image\n"
    "\t+ - cursor radio ++\n"
    "\t- - cursor radio --\n"
    "\ts - Save image as out.png\n"
    "\tc - Classify image, the result in console\n"
        "\tESC - quit the program\n");
    drawing=0;
    r=10;
    red=green=blue=0;
    last_x=last_y=red=green=blue=0;
    //Create image
    imagen=cvCreateImage(cvSize(128,128),IPL_DEPTH_8U,1);
    //Set data of image to white
    cvSet(imagen, CV_RGB(255,255,255),NULL);
    //Image we show user with cursor and other artefacts we need
    screenBuffer=cvCloneImage(imagen);

    //Create window
        cvNamedWindow( "Demo", 0 );

    cvResizeWindow("Demo", 128,128);
    //Create mouse CallBack
//    cvSetMouseCallback("Demo",&on_mouse, 0 );

    cv::Mat frame,temp;
    cv::VideoCapture cap(0);
    char S[255];
    int k = 0;
    //////////////////
    //My OCR
    //////////////////
    basicOCR ocr;

    //Main Loop
    for(;;)
    {
        int c;
        cap >> frame;
        cap >> temp;
        cvtColor(frame, temp, CV_RGB2GRAY);
        cv::threshold(temp,temp, 50, 255,CV_THRESH_BINARY);
        *imagen = temp;
        *screenBuffer = frame;
        cvShowImage( "Demo", screenBuffer );
        c = cvWaitKey(10);
        if( (char) c == 27 )
            break;
//    if( (char) c== '+' ){
//        r++;
//        drawCursor(last_x,last_y);
//    }
//    if( ((char)c== '-') && (r>1) ){
//        r--;
//        drawCursor(last_x,last_y);
//    }
//    if( (char)c== 'r'){
//        cvSet(imagen, cvRealScalar(255),NULL);
//        drawCursor(last_x,last_y);
//    }
    if( (char)c== 's'){
        if (k < 10)
            sprintf(S, "../../OCR/4/40%d.pbm", k);
        else
            sprintf(S, "../../OCR/4/4%d.pbm", k);
        cvSaveImage(S, imagen);
    }
    if( (char)c=='c'){
        ocr.classify(imagen,1);
    }

    }

    cvDestroyWindow("Demo");

    return 0;
}

#ifdef _EiC
main(1,"mouseEvent.c");
#endif
