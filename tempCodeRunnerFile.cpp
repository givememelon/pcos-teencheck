#include <bits/stdc++.h>
using namespace std;

int main() {
    int P = 364;
    int L = 79;
    int J;

    cin >> J;
    // operasi dasar
    cout << P * L << endl;
    cout << 2*(P+L) << endl;
    cout << P + L + J << endl;

    //fungsi KPK
    cout << lcm(3,7) << endl;

    //fungsi FPB
    cout << gcd(20,4) << endl;
}
