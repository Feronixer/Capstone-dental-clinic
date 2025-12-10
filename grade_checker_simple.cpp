#include <iostream>
#include <iomanip>

using namespace std;

// Function to convert grade to GPE (Grade Point Equivalence)
float convertToGPE(float grade) {
    if (grade >= 97 && grade <= 100) return 1.00;
    else if (grade >= 94) return 1.25;
    else if (grade >= 91) return 1.50;
    else if (grade >= 88) return 1.75;
    else if (grade >= 85) return 2.00;
    else if (grade >= 82) return 2.25;
    else if (grade >= 79) return 2.50;
    else if (grade >= 76) return 2.75;
    else if (grade >= 75) return 3.00;
    else return 5.00;  // Below 75 is failing
}

int main() {
    // Step 1: Define subjects
    string subjects[4] = {
        "Math",
        "Science",
        "English",
        "History"
    };
    
    int numSubjects = 4;
    float grades[4];
    float gpe[4];
    
    // Step 2: Display title
    cout << "===== GRADE POINT EQUIVALENCE SYSTEM =====\n\n";
    
    // Step 3: Get grades from user
    for (int i = 0; i < numSubjects; i++) {
        cout << "Enter grade for " << subjects[i] << ": ";
        cin >> grades[i];
        gpe[i] = convertToGPE(grades[i]);
    }
    
    // Step 4: Calculate average
    float sum = 0;
    for (int i = 0; i < numSubjects; i++) {
        sum = sum + grades[i];
    }
    float average = sum / numSubjects;
    float averageGPE = convertToGPE(average);
    
    // Step 5: Display results
    cout << "\n==========================================\n";
    cout << "Subject\t\tGrade\tGPE\n";
    cout << "------------------------------------------\n";
    
    for (int i = 0; i < numSubjects; i++) {
        cout << subjects[i] << "\t\t" 
             << (int)grades[i] << "\t" 
             << fixed << setprecision(2) << gpe[i] << endl;
    }
    
    cout << "------------------------------------------\n";
    cout << "Average\t\t" 
         << fixed << setprecision(2) << average << "\t" 
         << averageGPE << endl;
    cout << "==========================================\n";
    
    return 0;
}

