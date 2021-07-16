#ifndef HEADER_H
#define HEADER_H

#include <opencv/cv.h>
#include <opencv2/highgui/highgui.hpp>
#include <opencv2/ml/ml.hpp>
#include <opencv2/imgproc/imgproc.hpp>
#include <stdio.h>
#include <ctype.h>
#include <math.h>

class basicOCR{
    public:
        float classify(IplImage* img,int showResult);
        basicOCR ();
        void test();
    private:
        char file_path[255];
        int train_samples;
        int classes;
        CvMat* trainData;
        CvMat* trainClasses;
        int size;
        static const int K=10;
        CvKNearest *knn;
        void getData();
        void train();
};

IplImage preprocessing(IplImage* imgSrc,int new_width, int new_height);

#endif // HEADER_H
