#include <stdio.h>
#include <stdlib.h>



typedef struct tempBox{
    int h, w;
    struct tempBox* next;

}Box;

Box* makeFirstNode();
void fillNode(Box* box, int h, int w);
void displayABox(Box* box);
Box* makeAnotherBox(Box* box);
void displayAllBoxes(Box* head);
void freeMemory(Box* head);


int main(){
    Box ** box = calloc(size, sizeof(Box*));
    Box* head = box;
    fillNode(box, 10, 15);
    displayABox(box);

    // processOtherNode
    box = makeAnotherBox(box);
    fillNode(box, 20, 35);
    displayABox(box);

    box = makeAnotherBox(box);
    fillNode(box, 40, 70);
    displayABox(box);

    displayAllBoxes(head);

    freeMemory(head);

} // end main

Box* makeFirstNode(){
   Box* result = calloc(1, sizeof(Box));
        // make sure it works
        result->next = NULL;
        // this indicates the end of list
    
    printf("The address of the first node is: %p\n", result);
   return result; 
}

void fillNode(Box* box, int h, int w){
    box->h = h;
    box->w = w;
}

void displayABox(Box* box){
    int area = box->h * box->w;

    printf("Height: %i\nWidth: %i\nArea: %i\n", box->h, box->w, area);
}

Box* makeAnotherBox(Box* box){

    box->next = calloc(1, sizeof(Box));
    box = box->next;
    box->next = NULL;
    
    printf("The address of the current node is: %p\n", box);
    return box;
}

void displayAllBoxes(Box* head){
    printf("Displaying all boxes:\n");
    int area = 0;
    Box* temp = head;

    while (temp != NULL){
        displayABox(temp);
        temp = temp->next;
    }
}

void freeMemory(Box* head){
    Box* box = head;
    Box* temp = NULL;
    printf("Freeing memory...\n");

    while(box != NULL){
        printf("Free memory from %p\n", box);
        temp = box;
        box = box->next;
        free(temp);
    }
}
