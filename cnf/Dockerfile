FROM osrf/ros:noetic-desktop-full
RUN apt-get update
RUN apt-get install -y ros-noetic-catkin python3-catkin-tools
COPY . home/app/
WORKDIR /home/app/catkin_ws
RUN catkin config --extend /opt/ros/noetic
RUN echo "source /opt/ros/noetic/setup.sh" >> ~/.bashrc
WORKDIR /home/app/catkin_ws
RUN . /opt/ros/noetic/setup.sh && catkin_make
RUN echo "source /home/app/catkin_ws/devel/setup.bash" >> ~/.bashrc
WORKDIR /home/app
ENTRYPOINT ["tail", "-f", "/dev/null"]