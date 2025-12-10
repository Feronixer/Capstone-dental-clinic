#include <iostream>
#include <iomanip>
#include <vector>
#include <string>

using namespace std;

float convertToGPE(float grade) {
    if (grade > 100.0f) return -2.0f;
    if (grade >= 97 && grade <= 100) return 1.00;
    else if (grade >= 94) return 1.25;
    else if (grade >= 91) return 1.50;
    else if (grade >= 88) return 1.75;
    else if (grade >= 85) return 2.00;
    else if (grade >= 82) return 2.25;
    else if (grade >= 79) return 2.50;
    else if (grade >= 76) return 2.75;
    else if (grade == 75) return 3.00;
    else return 5.00;
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
        do {
            cout << "Enter grade in " << subjects[i] << ": ";
            cin >> grade[i];

            if (grade[i] > 100 || grade[i] < 0) {
                cout << "Error: grade must be between 0 and 100. Please try again.\n";
            }
        } while (grade[i] > 100 || grade[i] < 0);

        gpe[i] = convertToGPE(grade[i]);
    }

    // Compute average
    float sum = 0;
    for (float g : grade) sum += g;
    float average = sum / n;
    float finalGPE = convertToGPE(average);

    // OUTPUT TABLE
    cout << "\n==============================================================\n";
    cout << left << setw(40) << "Subject"
         << setw(12) << "Grade"
         << setw(12) << "GPE" << endl;
    cout << "--------------------------------------------------------------\n";

    for (int i = 0; i < n; i++) {
        cout << left << setw(40) << subjects[i]
             << setw(12) << (int)grade[i]      // NO decimal for grades
             << setw(12) << fixed << setprecision(2) << gpe[i]   // GPE stays 2 decimals
             << endl;
    }

    cout << "--------------------------------------------------------------\n";
    // Average stays with decimal points
    cout << left << setw(40) << "Average"
         << setw(12) << fixed << setprecision(2) << average   // KEEP decimals
         << setw(12) << fixed << setprecision(2) << finalGPE
         << endl;
    cout << "==============================================================\n";

    return 0;
}

