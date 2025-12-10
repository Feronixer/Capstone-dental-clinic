#include <iostream>
#include <iomanip>
#include <vector>
#include <string>

using namespace std;

float convertToGPE(float grade) {
    if (grade > 100.0f) return -2.0f;  // Error indicator
    if (grade < 0.0f) return -2.0f;    // Handle negative grades
    
    if (grade >= 97.0f && grade <= 100.0f) return 1.00f;
    else if (grade >= 94.0f) return 1.25f;
    else if (grade >= 91.0f) return 1.50f;
    else if (grade >= 88.0f) return 1.75f;
    else if (grade >= 85.0f) return 2.00f;
    else if (grade >= 82.0f) return 2.25f;
    else if (grade >= 79.0f) return 2.50f;
    else if (grade >= 76.0f) return 2.75f;
    else if (grade >= 75.0f) return 3.00f;  // FIXED: Changed from == 75 to >= 75
    else return 5.00f;  // Below 75 is failing
}

int main() {
    vector<string> subjects = {
        "Fundamentals of Surveying",
        "Computer Fundamentals & Programming",
        "Engineering Economics",
        "Differential Equation",
        "Computer-Aided Drafting",
        "Statics of Rigid Bodies",
        "Filipino sa Iba't Ibang Disiplina",
        "Environmental Science",
        "Sports"
    };

    int n = subjects.size();
    vector<float> grade(n);
    vector<float> gpe(n);

    cout << "===== STUDENT GRADE POINT EQUIVALENCE SYSTEM =====\n\n";

    // Input
    for (int i = 0; i < n; i++) {
        bool validInput = false;
        
        while (!validInput) {
            cout << "Enter grade in " << subjects[i] << ": ";
            cin >> grade[i];

            // Check for invalid grades
            if (grade[i] > 100.0f || grade[i] < 0.0f) {
                cout << "Error: grade must be between 0 and 100.\n";
                cout << "Please try again.\n\n";
                continue;  // Loop back to ask for input again
            }

            gpe[i] = convertToGPE(grade[i]);
            
            // Check if conversion returned error
            if (gpe[i] < 0) {
                cout << "Error: Invalid grade value.\n";
                cout << "Please try again.\n\n";
                continue;  // Loop back to ask for input again
            }
            
            validInput = true;  // Input is valid, exit the while loop
        }
    }

    // Compute average
    float sum = 0.0f;
    for (float g : grade) sum += g;
    float average = sum / static_cast<float>(n);  // Explicit cast for clarity
    float finalGPE = convertToGPE(average);
    
    if (finalGPE < 0) {
        cout << "Error: Invalid average calculated.\n";
        return 1;
    }

    // OUTPUT TABLE
    cout << "\n==============================================================\n";
    cout << left << setw(40) << "Subject"
         << setw(12) << "Grade"
         << setw(12) << "GPE" << endl;
    cout << "--------------------------------------------------------------\n";

    // Set precision once for the entire output
    cout << fixed << setprecision(2);
    
    for (int i = 0; i < n; i++) {
        cout << left << setw(40) << subjects[i]
             << setw(12) << static_cast<int>(grade[i])  // Truncate to int for display
             << setw(12) << gpe[i]
             << endl;
    }

    cout << "--------------------------------------------------------------\n";
    // Average stays with decimal points
    cout << left << setw(40) << "Average"
         << setw(12) << average
         << setw(12) << finalGPE
         << endl;
    cout << "==============================================================\n";

    return 0;
}

