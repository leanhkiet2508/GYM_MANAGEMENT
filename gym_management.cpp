#include <iostream>
#include <vector>
#include <string>
#include <iomanip>
#include <algorithm>
#include <ctime>

using namespace std;

// =================================================================================
// DATA STRUCTURES (MÔ HÌNH DỮ LIỆU - Dựa trên Class Diagram)
// =================================================================================

// Cấu trúc gói dịch vụ / sản phẩm
struct ServicePackage {
    string id;
    string name;
    double price;
    string description; // Mô tả tóm tắt/chi tiết
    string category;    // Membership, PT, Product...
    int quantity;       // Tồn kho hoặc slot
    int views;          // Số lượt xem

    // Constructor
    ServicePackage(string _id, string _name, double _price, string _desc, string _cat, int _qty)
        : id(_id), name(_name), price(_price), description(_desc), category(_cat), quantity(_qty), views(0) {}
};

// Cấu trúc giỏ hàng
struct CartItem {
    string serviceId;
    string name;
    double price;
    int quantity;
};

// Cấu trúc đơn hàng
struct Order {
    string orderId;
    string memberEmail;
    string receiverName;
    string receiverPhone;
    double totalAmount;
    string status; // "New", "Paid", "Activated", "Cancelled"
    string paymentMethod;
    vector<CartItem> details;
};

// Cấu trúc Hội viên (Member)
struct Member {
    string email; // Tên đăng nhập
    string password;
    string name;
    string phone;
    string address;
    // Thông tin sức khỏe cơ bản
    double height;
    double weight;
    
    vector<CartItem> cart; // Giỏ hàng riêng của hội viên
};

// Cấu trúc Admin
struct Admin {
    string username;
    string password;
};

// =================================================================================
// GLOBAL DATABASE (GIẢ LẬP CƠ SỞ DỮ LIỆU)
// =================================================================================

vector<ServicePackage> services;
vector<Member> members;
vector<Order> orders;
vector<Admin> admins;

// Trạng thái phiên làm việc hiện tại
Member* currentMember = nullptr;
Admin* currentAdmin = nullptr;

// =================================================================================
// UTILITY FUNCTIONS (HÀM HỖ TRỢ)
// =================================================================================

// Hàm khởi tạo dữ liệu mẫu
void seedData() {
    // Dữ liệu Admin
    admins.push_back({"admin", "123456"});

    // Dữ liệu Gói tập & Sản phẩm
    services.push_back(ServicePackage("SV001", "Goi tap 1 Thang", 500000, "Tap luyen tu do 1 thang", "Membership", 100));
    services.push_back(ServicePackage("SV002", "Goi tap 1 Nam", 5000000, "Tap luyen 12 thang + qua tang", "Membership", 100));
    services.push_back(ServicePackage("PT001", "PT 1 kem 1 (10 buoi)", 3000000, "Huan luyen vien ca nhan", "PT", 20));
    services.push_back(ServicePackage("SP001", "Whey Protein Isolate", 1500000, "Thuc pham bo sung tang co", "Supplement", 50));
    services.push_back(ServicePackage("SP002", "Gang tay Boxing", 450000, "Phu kien bao ve tay", "Accessory", 30));

    // Dữ liệu Member mẫu
    Member m;
    m.email = "hieu@gmail.com";
    m.password = "123456"; 
    m.name = "Nguyen Van Hieu";
    m.phone = "0909123456";
    m.address = "TP. Ho Chi Minh";
    members.push_back(m);
}

void clearScreen() {
    // Lệnh xóa màn hình đơn giản cho console (in nhiều dòng trống)
    cout << string(50, '\n'); 
}

void pause() {
    cout << "\nNhan Enter de tiep tuc...";
    cin.ignore();
    cin.get();
}

// =================================================================================
// USE-CASE IMPLEMENTATIONS (HIỆN THỰC HÓA CÁC CHỨC NĂNG)
// =================================================================================

// ---------------------------------------------------------
// UC: BROWSE SERVICE (Duyệt xem dịch vụ)
// UC: SEARCH SERVICE (Tìm kiếm dịch vụ)
// ---------------------------------------------------------
void viewServices(string keyword = "") {
    cout << "\n--- DANH SACH DICH VU & SAN PHAM ---\n";
    cout << left << setw(10) << "MA" << setw(25) << "TEN" << setw(15) << "GIA (VND)" << setw(15) << "LOAI" << "MO TA" << endl;
    cout << "--------------------------------------------------------------------------------\n";
    
    bool found = false;
    for (size_t i = 0; i < services.size(); ++i) {
        // Nếu có từ khóa, kiểm tra xem tên có chứa từ khóa không (Search Service)
        if (keyword != "" && services[i].name.find(keyword) == string::npos) {
            continue;
        }
        
        cout << left << setw(10) << services[i].id 
             << setw(25) << services[i].name 
             << setw(15) << (long)services[i].price 
             << setw(15) << services[i].category 
             << services[i].description << endl;
        services[i].views++; // Tăng lượt xem
        found = true;
    }

    if (!found) cout << "Khong tim thay san pham nao!\n";
}

// ---------------------------------------------------------
// UC: MANAGE CART (Quản lý giỏ hàng)
// ---------------------------------------------------------
void addToCart(string serviceId) {
    if (currentMember == nullptr) {
        cout << "Vui long dang nhap de them vao gio hang!\n";
        return;
    }

    // Tìm sản phẩm
    ServicePackage* selectedService = nullptr;
    for (size_t i = 0; i < services.size(); ++i) {
        if (services[i].id == serviceId) {
            selectedService = &services[i];
            break;
        }
    }

    if (selectedService) {
        // Kiểm tra xem đã có trong giỏ chưa
        bool exists = false;
        for (size_t i = 0; i < currentMember->cart.size(); ++i) {
            if (currentMember->cart[i].serviceId == serviceId) {
                currentMember->cart[i].quantity++;
                exists = true;
                break;
            }
        }
        if (!exists) {
            CartItem item;
            item.serviceId = selectedService->id;
            item.name = selectedService->name;
            item.price = selectedService->price;
            item.quantity = 1;
            currentMember->cart.push_back(item);
        }
        cout << "Da them [" << selectedService->name << "] vao gio hang.\n";
    } else {
        cout << "Ma san pham khong ton tai!\n";
    }
}

void viewCart() {
    if (currentMember == nullptr || currentMember->cart.empty()) {
        cout << "\nGio hang trong!\n";
        return;
    }

    cout << "\n--- GIO HANG CUA BAN ---\n";
    double total = 0;
    for (size_t i = 0; i < currentMember->cart.size(); ++i) {
        double subtotal = currentMember->cart[i].price * currentMember->cart[i].quantity;
        total += subtotal;
        cout << i + 1 << ". " << currentMember->cart[i].name 
             << " - SL: " << currentMember->cart[i].quantity 
             << " - Gia: " << (long)subtotal << " VND\n";
    }
    cout << "Tong cong: " << (long)total << " VND\n";
    
    // Tùy chọn cập nhật giỏ hàng
    cout << "\n(1) Xoa san pham khoi gio  (2) Thanh toan (Checkout)  (0) Quay lai\n";
    cout << "Chon: ";
    int choice; cin >> choice;
    if (choice == 1) {
        cout << "Nhap STT san pham muon xoa: ";
        int idx; cin >> idx;
        if (idx > 0 && idx <= (int)currentMember->cart.size()) {
            currentMember->cart.erase(currentMember->cart.begin() + idx - 1);
            cout << "Da xoa san pham.\n";
        }
    } else if (choice == 2) {
        // Điểm mở rộng sang Checkout
        return; // Sẽ được xử lý ở menu chính
    }
}

// ---------------------------------------------------------
// UC: CHECKOUT (Thanh toán)
// ---------------------------------------------------------
void checkout() {
    if (currentMember == nullptr || currentMember->cart.empty()) {
        cout << "Gio hang trong, khong the thanh toan!\n";
        return;
    }

    cout << "\n--- THANH TOAN (CHECKOUT) ---\n";
    double total = 0;
    for (size_t i = 0; i < currentMember->cart.size(); ++i) {
        total += (currentMember->cart[i].price * currentMember->cart[i].quantity);
    }

    // Tính phí vận chuyển
    double shippingFee = (total >= 1000000) ? 0 : 30000;
    cout << "Tong gia tri don hang: " << (long)total << " VND\n";
    cout << "Phi van chuyen/xu ly: " << (long)shippingFee << " VND\n";
    cout << "TONG THANH TOAN: " << (long)(total + shippingFee) << " VND\n";

    // Nhập thông tin người nhận
    string rcName, rcPhone;
    cout << "\nNhap ten nguoi nhan (Enter de dung ten mac dinh): ";
    cin.ignore();
    getline(cin, rcName);
    if (rcName == "") rcName = currentMember->name;

    cout << "Nhap SDT nguoi nhan: ";
    getline(cin, rcPhone);
    if (rcPhone == "") rcPhone = currentMember->phone;

    // Chọn phương thức thanh toán
    cout << "Chon phuong thuc thanh toan:\n1. Tien mat tai quay (COD)\n2. Truc tuyen (Payment Gateway)\nChon: ";
    int pMethod; cin >> pMethod;
    string methodStr = (pMethod == 1) ? "COD" : "Online";

    if (pMethod == 2) {
        cout << "Dang ket noi cong thanh toan...\n";
        // Giả lập thanh toán thành công
        cout << "Thanh toan thanh cong!\n";
    }

    // Tạo đơn hàng
    Order newOrder;
    newOrder.orderId = "ORD" + to_string(time(0)); // Mã đơn hàng theo thời gian
    newOrder.memberEmail = currentMember->email;
    newOrder.receiverName = rcName;
    newOrder.receiverPhone = rcPhone;
    newOrder.totalAmount = total + shippingFee;
    newOrder.status = (pMethod == 1) ? "New" : "Paid"; 
    newOrder.paymentMethod = methodStr;
    newOrder.details = currentMember->cart;

    orders.push_back(newOrder);
    
    // Xóa giỏ hàng sau khi đặt thành công
    currentMember->cart.clear();
    cout << "Dat hang thanh cong! Ma don hang: " << newOrder.orderId << endl;
    cout << "Email xac nhan da duoc gui.\n";
}

// ---------------------------------------------------------
// UC: REGISTER (Đăng ký hội viên)
// ---------------------------------------------------------
void registerMember() {
    Member m;
    cout << "\n--- DANG KY HOI VIEN MOI ---\n";
    cout << "Email: "; cin >> m.email;
    
    // Kiểm tra email tồn tại
    for (size_t i = 0; i < members.size(); ++i) {
        if (members[i].email == m.email) {
            cout << "Email da ton tai!\n";
            return;
        }
    }

    cout << "Mat khau: "; cin >> m.password;
    cout << "Ho ten: "; cin.ignore(); getline(cin, m.name);
    cout << "So dien thoai: "; getline(cin, m.phone);
    cout << "Dia chi: "; getline(cin, m.address);
    // Thông tin sức khỏe tùy chọn
    cout << "Chieu cao (cm): "; cin >> m.height;
    cout << "Can nang (kg): "; cin >> m.weight;

    members.push_back(m);
    cout << "Dang ky thanh cong! Vui long dang nhap.\n";
}

// ---------------------------------------------------------
// UC: LOGIN (Đăng nhập)
// ---------------------------------------------------------
bool login() {
    string email, pass;
    cout << "\n--- DANG NHAP ---\n";
    cout << "Email/Username: "; cin >> email;
    cout << "Password: "; cin >> pass;

    // Check Admin
    for (size_t i = 0; i < admins.size(); ++i) {
        if (admins[i].username == email && admins[i].password == pass) {
            currentAdmin = &admins[i];
            cout << "Dang nhap Admin thanh cong!\n";
            return true;
        }
    }

    // Check Member
    for (size_t i = 0; i < members.size(); ++i) {
        if (members[i].email == email && members[i].password == pass) {
            currentMember = &members[i];
            cout << "Xin chao Hoi vien " << members[i].name << "!\n";
            return true;
        }
    }

    cout << "Sai thong tin dang nhap!\n";
    return false;
}

// ---------------------------------------------------------
// UC: UPDATE PROFILE (Cập nhật hồ sơ)
// ---------------------------------------------------------
void updateProfile() {
    if (!currentMember) return;
    cout << "\n--- CAP NHAT HO SO ---\n";
    cout << "Ten hien tai: " << currentMember->name << "\nNhap ten moi (Enter de giu nguyen): ";
    cin.ignore();
    string val; 
    getline(cin, val); if (val != "") currentMember->name = val;

    cout << "SDT hien tai: " << currentMember->phone << "\nNhap SDT moi: ";
    getline(cin, val); if (val != "") currentMember->phone = val;

    cout << "Cap nhat thanh cong!\n";
}

// ---------------------------------------------------------
// UC: RESET PASSWORD (Khôi phục mật khẩu)
// ---------------------------------------------------------
void resetPassword() {
    string email;
    cout << "\n--- KHOI PHUC MAT KHAU ---\nNhap email: "; cin >> email;
    bool found = false;
    for (size_t i = 0; i < members.size(); ++i) {
        if (members[i].email == email) {
            cout << "Mat khau moi da duoc gui vao email " << email << "\n";
            found = true;
            break;
        }
    }
    if (!found) cout << "Email khong ton tai!\n";
}

// ---------------------------------------------------------
// UC: VIEW HISTORY (Xem lịch sử)
// UC: CANCEL ORDER (Hủy đơn hàng)
// ---------------------------------------------------------
void viewHistory() {
    if (!currentMember) return;
    cout << "\n--- LICH SU GIAO DICH ---\n";
    bool hasOrder = false;
    for (size_t i = 0; i < orders.size(); ++i) {
        if (orders[i].memberEmail == currentMember->email) {
            hasOrder = true;
            cout << "Ma Don: " << orders[i].orderId 
                 << " | Tong: " << (long)orders[i].totalAmount 
                 << " | Trang thai: " << orders[i].status << endl;
            
            // Chi tiết đơn hàng
            for (size_t j = 0; j < orders[i].details.size(); ++j) {
                cout << "   - " << orders[i].details[j].name << " x " << orders[i].details[j].quantity << endl;
            }

            // Hủy đơn hàng nếu trạng thái là "New"
            if (orders[i].status == "New") {
                cout << "   -> Ban co muon HUY don nay? (1: Co, 0: Khong): ";
                int chon; cin >> chon;
                if (chon == 1) {
                    orders[i].status = "Cancelled";
                    cout << "   -> Da huy don hang.\n";
                }
            }
            cout << "---------------------------\n";
        }
    }
    if (!hasOrder) cout << "Chua co giao dich nao.\n";
}

// ---------------------------------------------------------
// UC: MANAGE SERVICE (Quản lý dịch vụ - Admin)
// ---------------------------------------------------------
void manageServicesAdmin() {
    cout << "\n--- ADMIN: QUAN LY DICH VU ---\n";
    cout << "1. Xem danh sach\n2. Them moi\n3. Xoa\nChon: ";
    int choice; cin >> choice;
    
    if (choice == 1) viewServices();
    else if (choice == 2) {
        string id, name, desc, cat;
        double price;
        int qty;
        cout << "Nhap ID: "; cin >> id;
        cout << "Nhap Ten: "; cin.ignore(); getline(cin, name);
        cout << "Gia: "; cin >> price;
        cout << "Mo ta: "; cin.ignore(); getline(cin, desc);
        cout << "Loai (Membership/PT/Product): "; getline(cin, cat);
        cout << "So luong: "; cin >> qty;
        
        services.push_back(ServicePackage(id, name, price, desc, cat, qty));
        cout << "Them thanh cong!\n";
    }
    else if (choice == 3) {
        string id; cout << "Nhap ID can xoa: "; cin >> id;
        
        // Tìm và xóa thủ công để tránh lỗi compiler cũ
        bool deleted = false;
        for (size_t i = 0; i < services.size(); ++i) {
            if (services[i].id == id) {
                services.erase(services.begin() + i);
                deleted = true;
                break;
            }
        }
        
        if (deleted) {
            cout << "Da xoa!\n";
        } else {
            cout << "Khong tim thay ID.\n";
        }
    }
}

// ---------------------------------------------------------
// UC: MANAGE MEMBER (Quản lý hội viên - Admin)
// ---------------------------------------------------------
void manageMembersAdmin() {
    cout << "\n--- ADMIN: DANH SACH HOI VIEN ---\n";
    cout << left << setw(20) << "EMAIL" << setw(20) << "HO TEN" << setw(15) << "SDT" << endl;
    for (size_t i = 0; i < members.size(); ++i) {
        cout << left << setw(20) << members[i].email << setw(20) << members[i].name << setw(15) << members[i].phone << endl;
    }
}

// ---------------------------------------------------------
// UC: MANAGE ORDER (Quản lý đơn hàng - Admin)
// ---------------------------------------------------------
void manageOrdersAdmin() {
    cout << "\n--- ADMIN: QUAN LY DON HANG ---\n";
    for (size_t i = 0; i < orders.size(); ++i) {
        cout << "ID: " << orders[i].orderId << " | Khach: " << orders[i].memberEmail 
             << " | Status: " << orders[i].status << endl;
    }
    cout << "\nCap nhat trang thai don hang? (Nhap ID don hang hoac '0' de thoat): ";
    string oid; cin >> oid;
    if (oid != "0") {
        for (size_t i = 0; i < orders.size(); ++i) {
            if (orders[i].orderId == oid) {
                cout << "Nhap trang thai moi (Paid, Activated, Cancelled): ";
                string st; cin >> st;
                orders[i].status = st;
                cout << "Da cap nhat!\n";
                return;
            }
        }
        cout << "Khong tim thay don hang.\n";
    }
}

// =================================================================================
// MAIN PROGRAM CONTROL (MÀN HÌNH CHÍNH)
// =================================================================================

void guestMenu() {
    while (true) {
        clearScreen();
        cout << "=== GYM MANAGEMENT APP (GUEST) ===\n";
        cout << "1. Xem danh sach goi tap/san pham [Browse]\n";
        cout << "2. Tim kiem [Search]\n";
        cout << "3. Dang nhap [Login]\n";
        cout << "4. Dang ky [Register]\n";
        cout << "5. Quen mat khau [Reset Password]\n";
        cout << "0. Thoat\n";
        cout << "Lua chon cua ban: ";
        int choice; cin >> choice;

        switch (choice) {
            case 1: viewServices(); pause(); break;
            case 2: {
                string kw; cout << "Nhap tu khoa: "; cin.ignore(); getline(cin, kw);
                viewServices(kw); pause(); break;
            }
            case 3: 
                if (login()) return; // Chuyển sang menu tương ứng
                pause(); break;
            case 4: registerMember(); pause(); break;
            case 5: resetPassword(); pause(); break;
            case 0: exit(0);
            default: break;
        }
    }
}

void memberMenu() {
    while (true) {
        clearScreen();
        cout << "=== GYM FACE MEMBER: " << currentMember->name << " ===\n";
        cout << "1. Xem & Mua dich vu [Browse]\n";
        cout << "2. Tim kiem [Search]\n";
        cout << "3. Gio hang cua toi [Manage Cart]\n";
        cout << "4. Thanh toan [Checkout]\n";
        cout << "5. Lich su giao dich [History]\n";
        cout << "6. Cap nhat ho so [Update Profile]\n";
        cout << "0. Dang xuat\n";
        cout << "Lua chon: ";
        int choice; cin >> choice;

        switch (choice) {
            case 1: 
                viewServices(); 
                cout << "\nNhap MA san pham de them vao gio (hoac '0' de quay lai): ";
                {
                    string pid; cin >> pid;
                    if (pid != "0") addToCart(pid);
                }
                pause(); break;
            case 2: {
                string kw; cout << "Nhap tu khoa: "; cin.ignore(); getline(cin, kw);
                viewServices(kw);
                pause(); break;
            }
            case 3: viewCart(); pause(); break;
            case 4: checkout(); pause(); break;
            case 5: viewHistory(); pause(); break;
            case 6: updateProfile(); pause(); break;
            case 0: 
                currentMember = nullptr; // Logout
                return;
        }
    }
}

void adminMenu() {
    while (true) {
        clearScreen();
        cout << "=== GYM ADMIN PANEL ===\n";
        cout << "1. Quan ly Dich vu/San pham [Manage Service]\n";
        cout << "2. Quan ly Hoi vien [Manage Member]\n";
        cout << "3. Quan ly Don hang [Manage Order]\n";
        cout << "0. Dang xuat\n";
        cout << "Lua chon: ";
        int choice; cin >> choice;

        switch (choice) {
            case 1: manageServicesAdmin(); pause(); break;
            case 2: manageMembersAdmin(); pause(); break;
            case 3: manageOrdersAdmin(); pause(); break;
            case 0:
                currentAdmin = nullptr;
                return;
        }
    }
}

int main() {
    seedData(); // Tạo dữ liệu giả lập ban đầu

    while (true) {
        if (currentAdmin != nullptr) {
            adminMenu();
        } else if (currentMember != nullptr) {
            memberMenu();
        } else {
            guestMenu();
        }
    }
    return 0;
}