<?php
    class CustomerModel extends Database
    {
        function apiListCustomer($keyword,$page,$amountElement){
            $currentPage = $page * $amountElement;
            
            $query = "SELECT tk.ID, kh.hoTen, kh.ngaysinh, kh.gioiTinh, kh.sdt, kh.diachi, kh.ranks, kh.image, tk.email, tk.role 
                      FROM `taikhoan` as tk join khachhang as kh on kh.IDTK = tk.ID";

            if($keyword != null)
            {
                $query.= " WHERE tk.ID = '".$keyword."' or kh.hoTen like '".'%'.$keyword.'%'."'";
            }

            $query.= " GROUP BY tk.ID LIMIT $currentPage,$amountElement";
            $result = mysqli_query(self::$connect, $query);

            $array = array();

            while($rows = mysqli_fetch_assoc($result))
            {
                $array[] = $rows;
            }

            return json_encode($array); 
        }

        function lengthListCustomer($keyword){
            $query = "SELECT tk.ID, kh.hoTen, kh.ngaysinh, kh.gioiTinh, kh.sdt, kh.diachi, kh.ranks, kh.image, tk.email, tk.role 
            FROM `taikhoan` as tk join khachhang as kh on kh.IDTK = tk.ID";

            if($keyword != null)
            {
                $query.= " WHERE tk.ID = '".$keyword."' or kh.hoTen like '".'%'.$keyword.'%'."'";
            }
            
            $query.= " GROUP BY tk.ID";

            $result = mysqli_query(self::$connect, $query);
            $length = mysqli_num_rows($result);
            
            return $length;
        }

        function removeCustomer($ID)
        {
            $queryOrder = "SELECT ID FROM donhang WHERE IDKH = '".$ID."'";
            $result = mysqli_query(self::$connect, $queryOrder);

            while($row = mysqli_fetch_array($result))
            {
                $removeDetailOrder = "DELETE FROM chitietdonhang where IDDH = '".$row["ID"]."'";
                mysqli_query(self::$connect,$removeDetailOrder);
            }

            $removeOrder = "DELETE FROM donhang where IDKH = '".$ID."'";
            mysqli_query(self::$connect,$removeOrder);

            $removeReview = "DELETE FROM binhluan where IDKH = '".$ID."'";
            mysqli_query(self::$connect,$removeReview);

            $removeListLove = "DELETE FROM danhsachyeuthich where IDKH = '".$ID."'";
            mysqli_query(self::$connect,$removeListLove);
            
            $removeCart = "DELETE FROM giohang where IDKH = '".$ID."'";
            mysqli_query(self::$connect,$removeCart);

            $removeCustomer = "DELETE FROM khachhang where IDTK = '".$ID."'";
            mysqli_query(self::$connect,$removeCustomer);

            $removeAccount = "DELETE FROM taikhoan where ID = '".$ID."'";
            mysqli_query(self::$connect, $removeAccount);
        }

        function updateCustomer($ID, $name, $birthDay, $gender, $phone, $address, $rank, $role)
        {
            if (empty($phone)) {
                echo "Số điện thoại không được để trống"; // Hiển thị thông báo lỗi
                return false; // Trả về false nếu số điện thoại bị trống
            }
            $updateUser = "UPDATE `khachhang` 
                          SET `hoTen`='" . $name . "', `ngaysinh`='" . $birthDay . "',
                              `gioiTinh`='" . $gender . "', `sdt`='" . $phone . "', `diachi`='" . $address . "', `ranks`='" . $rank . "'
                           WHERE IDTK = '" . $ID . "'";
        
            $updateRole = "UPDATE `taikhoan` SET `role` = '" . $role . "' WHERE ID = '" . $ID . "'";
        
            $result1 = mysqli_query(self::$connect, $updateUser);
            $result2 = mysqli_query(self::$connect, $updateRole);
        
            // Kiểm tra kết quả của cả hai truy vấn
            if ($result1 && $result2) {
                // Cập nhật biến session $_SESSION["hoTen"] với tên mới
                $_SESSION["hoTen"] = $name;
                return true; // Trả về true nếu cập nhật thành công
            } else {
                return false; // Trả về false nếu cập nhật không thành công
            }
        }
        
        function updateAvatarCustomer($ID,$image)
        {
            $query = "UPDATE `khachhang` 
                      SET `image`='".$image."' WHERE IDTK = '".$ID."'";
            
            mysqli_query(self::$connect,$query);
        }
    }
?>