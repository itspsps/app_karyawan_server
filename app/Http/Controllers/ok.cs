string number = string.Empty;
string no_string = string.Empty;
string cYear = string.Empty;
string cMonth = string.Empty;
string cDay = string.Empty;
int lengthnumber  = 0;
int nomor_urut  = 0;
String GetCustNum = string.Empty;
int GetInvcNum = 0;
string depo = string.Empty;
string CreateLN = string.Empty;
string prod =  string.Empty;


// int InvoiceNum = ttInvcDtl[0].InvoiceNum;
// var ProdCode = ttInvcDtl[0].ProdCode;
foreach ( var ttInvcHead_Recs in ( from ttInvcHead_Row in ttInvcHead where ttInvcHead_Row.Company == Session.CompanyID && ttInvcHead_Row.RowMod == "A" || ttInvcHead_Row.RowMod == "U" select ttInvcHead_Row))
{
    if (ttInvcHead_Recs !=  null){
        GetCustNum  = ttInvcHead_Recs.CustNum.ToString();
        GetInvcNum   = ttInvcHead_Recs.InvoiceNum;

        Erp.Tables.Customer Customer;
        Erp.Tables.InvcDtl InvcDtl;
        Erp.Tables.ProdGrup ProdGrup;
        Erp.Tables.CustGrup CustGrup;
        
        var GetCustomer_Recs = 
            (from Customer_Row in Db.Customer where Customer_Row.Company == Session.CompanyID && Customer_Row.CustNum == ttInvcHead_Recs.CustNum select Customer_Row).FirstOrDefault();
        var GetCustGrup_Recs = 
            (from CustGrup_Row in Db.CustGrup where CustGrup_Row.Company == Session.CompanyID && CustGrup_Row.GroupCode == GetCustomer_Recs.GroupCode select CustGrup_Row).FirstOrDefault();
        {
            if ( GetCustGrup_Recs != null ){
               if(ttInvcHead_Recs.InvoiceType == "SHP"){
                  if(ttInvcHead_Recs.Plant == "BDG"){
                     //GET LN
                     CreateLN = "SI.BDG." + GetCustGrup_Recs.SPS_ProductGroup_c;
                     //BDG.BJ/23.08.01/0001
                     DateTime dt = Convert.ToDateTime(ttInvcHead_Recs.InvoiceDate);            
                     cYear = Convert.ToString(dt.Year);
                     cMonth = dt.Date.ToString("MM");
                     cDay = dt.Date.ToString("dd");   
                     number = CreateLN  + "/"+ cYear + "." + cMonth + "." + cDay + "/" ;
                     lengthnumber = number.Length;
                     //ttInvcHead_Recs["SPS_LegalNumber_c"] = number;
                     Erp.Tables.InvcHead InvcHead; 
                     var InvcHead_Recs = (from InvcHead_Row in Db.InvcHead 
                                          where InvcHead_Row.Company == Session.CompanyID && 
                                          InvcHead_Row.SPS_LegalNumber_c != "" && 
                                          InvcHead_Row.InvoiceType == ttInvcHead_Recs.InvoiceType &&
                                          InvcHead_Row.SPS_LegalNumber_c.StartsWith(number)  orderby InvcHead_Row.SPS_LegalNumber_c descending select InvcHead_Row).FirstOrDefault();
                     {
                        var InvcHead_Row = InvcHead_Recs;
                        if (InvcHead_Recs != null){
                           nomor_urut = Convert.ToInt32((InvcHead_Row["SPS_LegalNumber_c"].ToString()).Substring(lengthnumber, 4));
                           nomor_urut = nomor_urut + 1;
                           no_string = nomor_urut.ToString();
                           if (nomor_urut < 10)
                              //000(9) atau 000(1)
                              number = number + "000" + no_string;
                           else if (nomor_urut > 9 && nomor_urut < 100)
                              //00(10) atau 00(99)
                              number = number + "00" + no_string;
                           else if (nomor_urut > 99 && nomor_urut < 1000)
                              //0(100) atau 0(999)
                              number = number + "0" + no_string;
                           else
                              number = number + no_string + no_string;
                         }else{
                           number = number + "0001";
                         }
                     ttInvcHead_Recs["SPS_LegalNumber_c"] = number;
                     }
                  }else if(ttInvcHead_Recs.Plant == "JKT"){
                     //GET LN
                     CreateLN = "SI.JKT." + GetCustGrup_Recs.SPS_ProductGroup_c;
                     //JKT.BR/23.08.01/0001
                     DateTime dt = Convert.ToDateTime(ttInvcHead_Recs.InvoiceDate);            
                     cYear = Convert.ToString(dt.Year);
                     cMonth = dt.Date.ToString("MM");
                     cDay = dt.Date.ToString("dd");   
                     number = CreateLN  + "/"+ cYear + "." + cMonth + "." + cDay + "/" ;
                     lengthnumber = number.Length;
                     //ttInvcHead_Recs["SPS_LegalNumber_c"] = number;
                     Erp.Tables.InvcHead InvcHead; 
                     var InvcHead_Recs = (from InvcHead_Row in Db.InvcHead where InvcHead_Row.Company == Session.CompanyID && InvcHead_Row.SPS_LegalNumber_c != "" && InvcHead_Row.InvoiceType ==         ttInvcHead_Recs.InvoiceType && InvcHead_Row.SPS_LegalNumber_c.StartsWith(number)  orderby InvcHead_Row.SPS_LegalNumber_c descending select InvcHead_Row).FirstOrDefault();
                     {
                        var InvcHead_Row = InvcHead_Recs;
                        if (InvcHead_Recs != null){
                           nomor_urut = Convert.ToInt32((InvcHead_Row["SPS_LegalNumber_c"].ToString()).Substring(lengthnumber, 4));
                           nomor_urut = nomor_urut + 1;
                           no_string = nomor_urut.ToString();
                           if (nomor_urut < 10)
                              //000(9) atau 000(1)
                              number = number + "000" + no_string;
                           else if (nomor_urut > 9 && nomor_urut < 100)
                              //00(10) atau 00(99)
                              number = number + "00" + no_string;
                           else if (nomor_urut > 99 && nomor_urut < 1000)
                              //0(100) atau 0(999)
                              number = number + "0" + no_string;
                           else
                              number = number + no_string + no_string;
                         }else{
                           number = number + "0001";
                         }
                     ttInvcHead_Recs["SPS_LegalNumber_c"] = number;
                     }
                  }else if(ttInvcHead_Recs.Plant == "SNG"){
                     //GET LN
                     CreateLN = "SI.SNG." + GetCustGrup_Recs.SPS_ProductGroup_c;
                     //SMG.DD/23.08.01/0001
                     DateTime dt = Convert.ToDateTime(ttInvcHead_Recs.InvoiceDate);            
                     cYear = Convert.ToString(dt.Year);
                     cMonth = dt.Date.ToString("MM");
                     cDay = dt.Date.ToString("dd");   
                     number = CreateLN  + "/"+ cYear + "." + cMonth + "." + cDay + "/" ;
                     lengthnumber = number.Length;
                     //ttInvcHead_Recs["SPS_LegalNumber_c"] = number;
                     Erp.Tables.InvcHead InvcHead; 
                     var InvcHead_Recs = (from InvcHead_Row in Db.InvcHead where InvcHead_Row.Company == Session.CompanyID && InvcHead_Row.SPS_LegalNumber_c != "" && InvcHead_Row.InvoiceType ==         ttInvcHead_Recs.InvoiceType && InvcHead_Row.SPS_LegalNumber_c.StartsWith(number)  orderby InvcHead_Row.SPS_LegalNumber_c descending select InvcHead_Row).FirstOrDefault();
                     {
                        var InvcHead_Row = InvcHead_Recs;
                        if (InvcHead_Recs != null){
                           nomor_urut = Convert.ToInt32((InvcHead_Row["SPS_LegalNumber_c"].ToString()).Substring(lengthnumber, 4));
                           nomor_urut = nomor_urut + 1;
                           no_string = nomor_urut.ToString();
                           if (nomor_urut < 10)
                              //000(9) atau 000(1)
                              number = number + "000" + no_string;
                           else if (nomor_urut > 9 && nomor_urut < 100)
                              //00(10) atau 00(99)
                              number = number + "00" + no_string;
                           else if (nomor_urut > 99 && nomor_urut < 1000)
                              //0(100) atau 0(999)
                              number = number + "0" + no_string;
                           else
                              number = number + no_string + no_string;
                         }else{
                           number = number + "0001";
                         }
                     ttInvcHead_Recs["SPS_LegalNumber_c"] = number;
                     }
                  }else if(ttInvcHead_Recs.Plant == "DPS"){
                     //GET LN
                     CreateLN = "SI.DPS." + GetCustGrup_Recs.SPS_ProductGroup_c;
                     //SMG.DD/23.08.01/0001
                     DateTime dt = Convert.ToDateTime(ttInvcHead_Recs.InvoiceDate);            
                     cYear = Convert.ToString(dt.Year);
                     cMonth = dt.Date.ToString("MM");
                     cDay = dt.Date.ToString("dd");   
                     number = CreateLN  + "/"+ cYear + "." + cMonth + "." + cDay + "/" ;
                     lengthnumber = number.Length;
                     //ttInvcHead_Recs["SPS_LegalNumber_c"] = number;
                     Erp.Tables.InvcHead InvcHead; 
                     var InvcHead_Recs = (from InvcHead_Row in Db.InvcHead where InvcHead_Row.Company == Session.CompanyID && InvcHead_Row.SPS_LegalNumber_c != "" && InvcHead_Row.InvoiceType ==         ttInvcHead_Recs.InvoiceType && InvcHead_Row.SPS_LegalNumber_c.StartsWith(number)  orderby InvcHead_Row.SPS_LegalNumber_c descending select InvcHead_Row).FirstOrDefault();
                     {
                        var InvcHead_Row = InvcHead_Recs;
                        if (InvcHead_Recs != null){
                           nomor_urut = Convert.ToInt32((InvcHead_Row["SPS_LegalNumber_c"].ToString()).Substring(lengthnumber, 4));
                           nomor_urut = nomor_urut + 1;
                           no_string = nomor_urut.ToString();
                           if (nomor_urut < 10)
                              //000(9) atau 000(1)
                              number = number + "000" + no_string;
                           else if (nomor_urut > 9 && nomor_urut < 100)
                              //00(10) atau 00(99)
                              number = number + "00" + no_string;
                           else if (nomor_urut > 99 && nomor_urut < 1000)
                              //0(100) atau 0(999)
                              number = number + "0" + no_string;
                           else
                              number = number + no_string + no_string;
                         }else{
                           number = number + "0001";
                         }
                     ttInvcHead_Recs["SPS_LegalNumber_c"] = number;
                     }
                  }else if(ttInvcHead_Recs.Plant == "SMG"){
                     //GET LN
                     CreateLN = "SI.SMG." + GetCustGrup_Recs.SPS_ProductGroup_c;
                     //SMG.DD/23.08.01/0001
                     DateTime dt = Convert.ToDateTime(ttInvcHead_Recs.InvoiceDate);            
                     cYear = Convert.ToString(dt.Year);
                     cMonth = dt.Date.ToString("MM");
                     cDay = dt.Date.ToString("dd");   
                     number = CreateLN  + "/"+ cYear + "." + cMonth + "." + cDay + "/" ;
                     lengthnumber = number.Length;
                     //ttInvcHead_Recs["SPS_LegalNumber_c"] = number;
                     Erp.Tables.InvcHead InvcHead; 
                     var InvcHead_Recs = (from InvcHead_Row in Db.InvcHead where InvcHead_Row.Company == Session.CompanyID && InvcHead_Row.SPS_LegalNumber_c != "" && InvcHead_Row.InvoiceType ==         ttInvcHead_Recs.InvoiceType && InvcHead_Row.SPS_LegalNumber_c.StartsWith(number)  orderby InvcHead_Row.SPS_LegalNumber_c descending select InvcHead_Row).FirstOrDefault();
                     {
                        var InvcHead_Row = InvcHead_Recs;
                        if (InvcHead_Recs != null){
                           nomor_urut = Convert.ToInt32((InvcHead_Row["SPS_LegalNumber_c"].ToString()).Substring(lengthnumber, 4));
                           nomor_urut = nomor_urut + 1;
                           no_string = nomor_urut.ToString();
                           if (nomor_urut < 10)
                              //000(9) atau 000(1)
                              number = number + "000" + no_string;
                           else if (nomor_urut > 9 && nomor_urut < 100)
                              //00(10) atau 00(99)
                              number = number + "00" + no_string;
                           else if (nomor_urut > 99 && nomor_urut < 1000)
                              //0(100) atau 0(999)
                              number = number + "0" + no_string;
                           else
                              number = number + no_string + no_string;
                         }else{
                           number = number + "0001";
                         }
                     ttInvcHead_Recs["SPS_LegalNumber_c"] = number;
                     }
                  }else if(ttInvcHead_Recs.Plant == "NGW"){
                     //GET LN
                     CreateLN = "SI.NGW." + GetCustGrup_Recs.SPS_ProductGroup_c;
                     //SMG.DD/23.08.01/0001
                     DateTime dt = Convert.ToDateTime(ttInvcHead_Recs.InvoiceDate);            
                     cYear = Convert.ToString(dt.Year);
                     cMonth = dt.Date.ToString("MM");
                     cDay = dt.Date.ToString("dd");   
                     number = CreateLN  + "/"+ cYear + "." + cMonth + "." + cDay + "/" ;
                     lengthnumber = number.Length;
                     //ttInvcHead_Recs["SPS_LegalNumber_c"] = number;
                     Erp.Tables.InvcHead InvcHead; 
                     var InvcHead_Recs = (from InvcHead_Row in Db.InvcHead where InvcHead_Row.Company == Session.CompanyID && InvcHead_Row.SPS_LegalNumber_c != "" && InvcHead_Row.InvoiceType ==         ttInvcHead_Recs.InvoiceType && InvcHead_Row.SPS_LegalNumber_c.StartsWith(number)  orderby InvcHead_Row.SPS_LegalNumber_c descending select InvcHead_Row).FirstOrDefault();
                     {
                        var InvcHead_Row = InvcHead_Recs;
                        if (InvcHead_Recs != null){
                           nomor_urut = Convert.ToInt32((InvcHead_Row["SPS_LegalNumber_c"].ToString()).Substring(lengthnumber, 4));
                           nomor_urut = nomor_urut + 1;
                           no_string = nomor_urut.ToString();
                           if (nomor_urut < 10)
                              //000(9) atau 000(1)
                              number = number + "000" + no_string;
                           else if (nomor_urut > 9 && nomor_urut < 100)
                              //00(10) atau 00(99)
                              number = number + "00" + no_string;
                           else if (nomor_urut > 99 && nomor_urut < 1000)
                              //0(100) atau 0(999)
                              number = number + "0" + no_string;
                           else
                              number = number + no_string + no_string;
                         }else{
                           number = number + "0001";
                         }
                     ttInvcHead_Recs["SPS_LegalNumber_c"] = number;
                     }
                  }else{
                     //GET LN
                     CreateLN = "SI.KDR." + GetCustGrup_Recs.SPS_ProductGroup_c;
                     //SI.SKM/23.08.01/0001
                     DateTime dt = Convert.ToDateTime(ttInvcHead_Recs.InvoiceDate);            
                     cYear = Convert.ToString(dt.Year);
                     cMonth = dt.Date.ToString("MM");
                     cDay = dt.Date.ToString("dd");   
                     number = CreateLN  + "/"+ cYear + "." + cMonth + "." + cDay + "/" ;
                     lengthnumber = number.Length;
                     ttInvcHead_Recs["SPS_LegalNumber_c"] = number;
                     Erp.Tables.InvcHead InvcHead; 
                     var InvcHead_Recs = (from InvcHead_Row in Db.InvcHead where InvcHead_Row.Company == Session.CompanyID && InvcHead_Row.SPS_LegalNumber_c != "" && InvcHead_Row.InvoiceType ==         ttInvcHead_Recs.InvoiceType && InvcHead_Row.SPS_LegalNumber_c.StartsWith(number)  orderby InvcHead_Row.SPS_LegalNumber_c descending select InvcHead_Row).FirstOrDefault();
                     
                     // 0
                     // ->0001
                     
                     {
                        var InvcHead_Row = InvcHead_Recs;
                        if (InvcHead_Recs != null){
                            // ERROR
                           no_string = InvcHead_Recs.ToString(); 
                           //this.PublishInfoMessage(no_string, Ice.Common.BusinessObjectMessageType.Information, Ice.Bpm.InfoMessageDisplayMode.Individual, "FirstVar","SecondVar");
                           nomor_urut = Convert.ToInt32((InvcHead_Recs["SPS_LegalNumber_c"].ToString()).Substring(lengthnumber, 4));
                           //this.PublishInfoMessage(nomor_urut.ToString(), Ice.Common.BusinessObjectMessageType.Information, Ice.Bpm.InfoMessageDisplayMode.Individual, "FirstVar","SecondVar");
                           // 0001
                           // 0001 + 1 -> 0002
                           nomor_urut = nomor_urut + 1;
                           no_string = nomor_urut.ToString();
                           if (nomor_urut < 10)
                              //000(9) atau 000(1)
                              number = number + "000" + no_string;
                           else if (nomor_urut > 9 && nomor_urut < 100)
                              //00(10) atau 00(99)
                              number = number + "00" + no_string;
                           else if (nomor_urut > 99 && nomor_urut < 1000)
                              //0(100) atau 0(999)
                              number = number + "0" + no_string;
                           else
                              number = number + no_string + no_string;
                         }else{
                           number = number + "0001";
                         }
                        //this.PublishInfoMessage("Invoice NULL", Ice.Common.BusinessObjectMessageType.Information, Ice.Bpm.InfoMessageDisplayMode.Individual, "FirstVar","SecondVar");
                        ttInvcHead_Recs["SPS_LegalNumber_c"] = number;
                     }
                  } 
               }else if (ttInvcHead_Recs.InvoiceType == "DEP"){
                  //GET LN
                  CreateLN = "DP." + GetCustGrup_Recs.SPS_ProductGroup_c;  
                  //DP.BJ.08.23.0100
                  DateTime dt = Convert.ToDateTime(ttInvcHead_Recs.InvoiceDate);            
                  cYear = Convert.ToString(dt.Year);
                  cMonth = dt.Date.ToString("MM");
                  cDay = dt.Date.ToString("dd");   
                  number = CreateLN  + "/"+ cYear + "." + cMonth + "." + cDay + "/" ;
                  lengthnumber = number.Length;
                  //ttInvcHead_Recs["SPS_LegalNumber_c"] = number;
                  Erp.Tables.InvcHead InvcHead; 
                  var InvcHead_Recs = (from InvcHead_Row in Db.InvcHead where InvcHead_Row.Company == Session.CompanyID && InvcHead_Row.SPS_LegalNumber_c != "" && InvcHead_Row.InvoiceType ==         ttInvcHead_Recs.InvoiceType && InvcHead_Row.SPS_LegalNumber_c.StartsWith(number)  orderby InvcHead_Row.SPS_LegalNumber_c descending select InvcHead_Row).FirstOrDefault();
                  {
                     var InvcHead_Row = InvcHead_Recs;
                     if (InvcHead_Recs != null){
                        nomor_urut = Convert.ToInt32((InvcHead_Row["SPS_LegalNumber_c"].ToString()).Substring(lengthnumber, 4));
                        nomor_urut = nomor_urut + 1;
                        no_string = nomor_urut.ToString();
                           if (nomor_urut < 10)
                              //000(9) atau 000(1)
                              number = number + "000" + no_string;
                           else if (nomor_urut > 9 && nomor_urut < 100)
                              //00(10) atau 00(99)
                              number = number + "00" + no_string;
                           else if (nomor_urut > 99 && nomor_urut < 1000)
                              //0(100) atau 0(999)
                              number = number + "0" + no_string;
                           else
                              number = number + no_string + no_string;
                         }else{
                           number = number + "0001";
                         }
                  ttInvcHead_Recs["SPS_LegalNumber_c"] = number;
                  }
               }else{
                  if(ttInvcHead_Recs.RMANum == 0 && 
                     Convert.ToBoolean(ttInvcHead_Recs.CreditMemo) == true && 
                     Convert.ToBoolean(ttInvcHead_Recs["PTI_Shipment_c"]) == false && 
                     Convert.ToBoolean(ttInvcHead_Recs.CorrectionInv) ==  false){
                     //RT.
                     if(ttInvcHead_Recs.Plant == "BDG"){
                        //GET LN
                        CreateLN = "RT.BDG";
                        //RT.BDG.0001
                        DateTime dt = Convert.ToDateTime(ttInvcHead_Recs.InvoiceDate);            
                        cYear = Convert.ToString(dt.Year);
                        cMonth = dt.Date.ToString("MM");
                        cDay = dt.Date.ToString("dd");   
                        number = CreateLN  + "/"+ cYear + "." + cMonth + "." + cDay + "/" ;
                        lengthnumber = number.Length;
                        //ttInvcHead_Recs["SPS_LegalNumber_c"] = number;
                        Erp.Tables.InvcHead InvcHead; 
                        var InvcHead_Recs = (from InvcHead_Row in Db.InvcHead where InvcHead_Row.Company == Session.CompanyID && InvcHead_Row.SPS_LegalNumber_c != "" && InvcHead_Row.InvoiceType ==         ttInvcHead_Recs.InvoiceType && InvcHead_Row.SPS_LegalNumber_c.StartsWith(number)  orderby InvcHead_Row.SPS_LegalNumber_c descending select InvcHead_Row).FirstOrDefault();
                        {
                           var InvcHead_Row = InvcHead_Recs;
                           if (InvcHead_Recs != null){
                              nomor_urut = Convert.ToInt32((InvcHead_Row["SPS_LegalNumber_c"].ToString()).Substring(lengthnumber, 4));
                              nomor_urut = nomor_urut + 1;
                              no_string = nomor_urut.ToString();
                           if (nomor_urut < 10)
                              //000(9) atau 000(1)
                              number = number + "000" + no_string;
                           else if (nomor_urut > 9 && nomor_urut < 100)
                              //00(10) atau 00(99)
                              number = number + "00" + no_string;
                           else if (nomor_urut > 99 && nomor_urut < 1000)
                              //0(100) atau 0(999)
                              number = number + "0" + no_string;
                           else
                              number = number + no_string + no_string;
                         }else{
                           number = number + "0001";
                         }
                           ttInvcHead_Recs["SPS_LegalNumber_c"] = number;
                        }
                     }else if (ttInvcHead_Recs.Plant == "JKT"){
                        CreateLN = "RT.JKT";
                        DateTime dt = Convert.ToDateTime(ttInvcHead_Recs.InvoiceDate);            
                        cYear = Convert.ToString(dt.Year);
                        cMonth = dt.Date.ToString("MM");
                        cDay = dt.Date.ToString("dd");   
                        number = CreateLN  + "/"+ cYear + "." + cMonth + "." + cDay + "/" ;
                        lengthnumber = number.Length;
                        //ttInvcHead_Recs["SPS_LegalNumber_c"] = number;
                        Erp.Tables.InvcHead InvcHead; 
                        var InvcHead_Recs = (from InvcHead_Row in Db.InvcHead where InvcHead_Row.Company == Session.CompanyID && InvcHead_Row.SPS_LegalNumber_c != "" && InvcHead_Row.InvoiceType ==         ttInvcHead_Recs.InvoiceType && InvcHead_Row.SPS_LegalNumber_c.StartsWith(number)  orderby InvcHead_Row.SPS_LegalNumber_c descending select InvcHead_Row).FirstOrDefault();
                        {
                           var InvcHead_Row = InvcHead_Recs;
                           if (InvcHead_Recs != null){
                              nomor_urut = Convert.ToInt32((InvcHead_Row["SPS_LegalNumber_c"].ToString()).Substring(lengthnumber, 4));
                              nomor_urut = nomor_urut + 1;
                              no_string = nomor_urut.ToString();
                           if (nomor_urut < 10)
                              //000(9) atau 000(1)
                              number = number + "000" + no_string;
                           else if (nomor_urut > 9 && nomor_urut < 100)
                              //00(10) atau 00(99)
                              number = number + "00" + no_string;
                           else if (nomor_urut > 99 && nomor_urut < 1000)
                              //0(100) atau 0(999)
                              number = number + "0" + no_string;
                           else
                              number = number + no_string + no_string;
                         }else{
                           number = number + "0001";
                         }
                           ttInvcHead_Recs["SPS_LegalNumber_c"] = number;
                        }
                     }else if (ttInvcHead_Recs.Plant == "NGW"){
                        CreateLN = "RT.NGW";
                        DateTime dt = Convert.ToDateTime(ttInvcHead_Recs.InvoiceDate);            
                        cYear = Convert.ToString(dt.Year);
                        cMonth = dt.Date.ToString("MM");
                        cDay = dt.Date.ToString("dd");   
                        number = CreateLN  + "/"+ cYear + "." + cMonth + "." + cDay + "/" ;
                        lengthnumber = number.Length;
                        //ttInvcHead_Recs["SPS_LegalNumber_c"] = number;
                        Erp.Tables.InvcHead InvcHead; 
                        var InvcHead_Recs = (from InvcHead_Row in Db.InvcHead where InvcHead_Row.Company == Session.CompanyID && InvcHead_Row.SPS_LegalNumber_c != "" && InvcHead_Row.InvoiceType == ttInvcHead_Recs.InvoiceType && InvcHead_Row.SPS_LegalNumber_c.StartsWith(number)  orderby InvcHead_Row.SPS_LegalNumber_c descending select InvcHead_Row).FirstOrDefault();
                        {
                           var InvcHead_Row = InvcHead_Recs;
                           if (InvcHead_Recs != null){
                              nomor_urut = Convert.ToInt32((InvcHead_Row["SPS_LegalNumber_c"].ToString()).Substring(lengthnumber, 4));
                              nomor_urut = nomor_urut + 1;
                              no_string = nomor_urut.ToString();
                           if (nomor_urut < 10)
                              //000(9) atau 000(1)
                              number = number + "000" + no_string;
                           else if (nomor_urut > 9 && nomor_urut < 100)
                              //00(10) atau 00(99)
                              number = number + "00" + no_string;
                           else if (nomor_urut > 99 && nomor_urut < 1000)
                              //0(100) atau 0(999)
                              number = number + "0" + no_string;
                           else
                              number = number + no_string + no_string;
                         }else{
                           number = number + "0001";
                         }
                           ttInvcHead_Recs["SPS_LegalNumber_c"] = number;
                        }
                     }else if (ttInvcHead_Recs.Plant == "SNG"){
                        CreateLN = "RT.SNG";
                        DateTime dt = Convert.ToDateTime(ttInvcHead_Recs.InvoiceDate);            
                        cYear = Convert.ToString(dt.Year);
                        cMonth = dt.Date.ToString("MM");
                        cDay = dt.Date.ToString("dd");   
                        number = CreateLN  + "/"+ cYear + "." + cMonth + "." + cDay + "/" ;
                        lengthnumber = number.Length;
                        //ttInvcHead_Recs["SPS_LegalNumber_c"] = number;
                        Erp.Tables.InvcHead InvcHead; 
                        var InvcHead_Recs = (from InvcHead_Row in Db.InvcHead where InvcHead_Row.Company == Session.CompanyID && InvcHead_Row.SPS_LegalNumber_c != "" && InvcHead_Row.InvoiceType ==         ttInvcHead_Recs.InvoiceType && InvcHead_Row.SPS_LegalNumber_c.StartsWith(number)  orderby InvcHead_Row.SPS_LegalNumber_c descending select InvcHead_Row).FirstOrDefault();
                        {
                           var InvcHead_Row = InvcHead_Recs;
                           if (InvcHead_Recs != null){
                              nomor_urut = Convert.ToInt32((InvcHead_Row["SPS_LegalNumber_c"].ToString()).Substring(lengthnumber, 4));
                              nomor_urut = nomor_urut + 1;
                              no_string = nomor_urut.ToString();
                           if (nomor_urut < 10)
                              //000(9) atau 000(1)
                              number = number + "000" + no_string;
                           else if (nomor_urut > 9 && nomor_urut < 100)
                              //00(10) atau 00(99)
                              number = number + "00" + no_string;
                           else if (nomor_urut > 99 && nomor_urut < 1000)
                              //0(100) atau 0(999)
                              number = number + "0" + no_string;
                           else
                              number = number + no_string + no_string;
                         }else{
                           number = number + "0001";
                         }
                           ttInvcHead_Recs["SPS_LegalNumber_c"] = number;
                        }
                     }else if (ttInvcHead_Recs.Plant == "DPS"){
                        CreateLN = "RT.DPS";
                        DateTime dt = Convert.ToDateTime(ttInvcHead_Recs.InvoiceDate);            
                        cYear = Convert.ToString(dt.Year);
                        cMonth = dt.Date.ToString("MM");
                        cDay = dt.Date.ToString("dd");   
                        number = CreateLN  + "/"+ cYear + "." + cMonth + "." + cDay + "/" ;
                        lengthnumber = number.Length;
                        //ttInvcHead_Recs["SPS_LegalNumber_c"] = number;
                        Erp.Tables.InvcHead InvcHead; 
                        var InvcHead_Recs = (from InvcHead_Row in Db.InvcHead where InvcHead_Row.Company == Session.CompanyID && InvcHead_Row.SPS_LegalNumber_c != "" && InvcHead_Row.InvoiceType ==         ttInvcHead_Recs.InvoiceType && InvcHead_Row.SPS_LegalNumber_c.StartsWith(number)  orderby InvcHead_Row.SPS_LegalNumber_c descending select InvcHead_Row).FirstOrDefault();
                        {
                           var InvcHead_Row = InvcHead_Recs;
                           if (InvcHead_Recs != null){
                              nomor_urut = Convert.ToInt32((InvcHead_Row["SPS_LegalNumber_c"].ToString()).Substring(lengthnumber, 4));
                              nomor_urut = nomor_urut + 1;
                              no_string = nomor_urut.ToString();
                           if (nomor_urut < 10)
                              //000(9) atau 000(1)
                              number = number + "000" + no_string;
                           else if (nomor_urut > 9 && nomor_urut < 100)
                              //00(10) atau 00(99)
                              number = number + "00" + no_string;
                           else if (nomor_urut > 99 && nomor_urut < 1000)
                              //0(100) atau 0(999)
                              number = number + "0" + no_string;
                           else
                              number = number + no_string + no_string;
                         }else{
                           number = number + "0001";
                         }
                           ttInvcHead_Recs["SPS_LegalNumber_c"] = number;
                        }
                     }else if (ttInvcHead_Recs.Plant == "SMG"){
                        CreateLN = "RT.SMG";
                        DateTime dt = Convert.ToDateTime(ttInvcHead_Recs.InvoiceDate);            
                        cYear = Convert.ToString(dt.Year);
                        cMonth = dt.Date.ToString("MM");
                        cDay = dt.Date.ToString("dd");   
                        number = CreateLN  + "/"+ cYear + "." + cMonth + "." + cDay + "/" ;
                        lengthnumber = number.Length;
                        //ttInvcHead_Recs["SPS_LegalNumber_c"] = number;
                        Erp.Tables.InvcHead InvcHead; 
                        var InvcHead_Recs = (from InvcHead_Row in Db.InvcHead where InvcHead_Row.Company == Session.CompanyID && InvcHead_Row.SPS_LegalNumber_c != "" && InvcHead_Row.InvoiceType ==         ttInvcHead_Recs.InvoiceType && InvcHead_Row.SPS_LegalNumber_c.StartsWith(number)  orderby InvcHead_Row.SPS_LegalNumber_c descending select InvcHead_Row).FirstOrDefault();
                        {
                           var InvcHead_Row = InvcHead_Recs;
                           if (InvcHead_Recs != null){
                              nomor_urut = Convert.ToInt32((InvcHead_Row["SPS_LegalNumber_c"].ToString()).Substring(lengthnumber, 4));
                              nomor_urut = nomor_urut + 1;
                              no_string = nomor_urut.ToString();
                           if (nomor_urut < 10)
                              //000(9) atau 000(1)
                              number = number + "000" + no_string;
                           else if (nomor_urut > 9 && nomor_urut < 100)
                              //00(10) atau 00(99)
                              number = number + "00" + no_string;
                           else if (nomor_urut > 99 && nomor_urut < 1000)
                              //0(100) atau 0(999)
                              number = number + "0" + no_string;
                           else
                              number = number + no_string + no_string;
                         }else{
                           number = number + "0001";
                         }
                           ttInvcHead_Recs["SPS_LegalNumber_c"] = number;
                        }
                     }else{
                        CreateLN = "RT.KDR";
                        DateTime dt = Convert.ToDateTime(ttInvcHead_Recs.InvoiceDate);            
                        cYear = Convert.ToString(dt.Year);
                        cMonth = dt.Date.ToString("MM");
                        cDay = dt.Date.ToString("dd");   
                        number = CreateLN  + "/"+ cYear + "." + cMonth + "." + cDay + "/" ;
                        lengthnumber = number.Length;
                        //ttInvcHead_Recs["SPS_LegalNumber_c"] = number;
                        Erp.Tables.InvcHead InvcHead; 
                        var InvcHead_Recs = (from InvcHead_Row in Db.InvcHead where InvcHead_Row.Company == Session.CompanyID && InvcHead_Row.SPS_LegalNumber_c != "" && InvcHead_Row.InvoiceType ==         ttInvcHead_Recs.InvoiceType && InvcHead_Row.SPS_LegalNumber_c.StartsWith(number)  orderby InvcHead_Row.SPS_LegalNumber_c descending select InvcHead_Row).FirstOrDefault();
                        {
                           var InvcHead_Row = InvcHead_Recs;
                           if (InvcHead_Recs != null){
                              nomor_urut = Convert.ToInt32((InvcHead_Row["SPS_LegalNumber_c"].ToString()).Substring(lengthnumber, 4));
                              nomor_urut = nomor_urut + 1;
                              no_string = nomor_urut.ToString();
                           if (nomor_urut < 10)
                              //000(9) atau 000(1)
                              number = number + "000" + no_string;
                           else if (nomor_urut > 9 && nomor_urut < 100)
                              //00(10) atau 00(99)
                              number = number + "00" + no_string;
                           else if (nomor_urut > 99 && nomor_urut < 1000)
                              //0(100) atau 0(999)
                              number = number + "0" + no_string;
                           else
                              number = number + no_string + no_string;
                         }else{
                           number = number + "0001";
                         }
                           ttInvcHead_Recs["SPS_LegalNumber_c"] = number;
                        }
                     }
                  }else if(ttInvcHead_Recs.RMANum != 0 && 
                     Convert.ToBoolean(ttInvcHead_Recs.CreditMemo) == true && 
                     Convert.ToBoolean(ttInvcHead_Recs["PTI_Shipment_c"]) == false && 
                     Convert.ToBoolean(ttInvcHead_Recs.CorrectionInv) ==  false){
                     //RT.
                     if(ttInvcHead_Recs.Plant == "BDG"){
                        //GET LN
                        CreateLN = "RT.BDG";
                        //RT.BDG.0001
                        DateTime dt = Convert.ToDateTime(ttInvcHead_Recs.InvoiceDate);            
                        cYear = Convert.ToString(dt.Year);
                        cMonth = dt.Date.ToString("MM");
                        cDay = dt.Date.ToString("dd");   
                        number = CreateLN  + "/"+ cYear + "." + cMonth + "." + cDay + "/" ;
                        lengthnumber = number.Length;
                        //ttInvcHead_Recs["SPS_LegalNumber_c"] = number;
                        Erp.Tables.InvcHead InvcHead; 
                        var InvcHead_Recs = (from InvcHead_Row in Db.InvcHead where InvcHead_Row.Company == Session.CompanyID && InvcHead_Row.SPS_LegalNumber_c != "" && InvcHead_Row.InvoiceType ==         ttInvcHead_Recs.InvoiceType && InvcHead_Row.SPS_LegalNumber_c.StartsWith(number)  orderby InvcHead_Row.SPS_LegalNumber_c descending select InvcHead_Row).FirstOrDefault();
                        {
                           var InvcHead_Row = InvcHead_Recs;
                           if (InvcHead_Recs != null){
                              nomor_urut = Convert.ToInt32((InvcHead_Row["SPS_LegalNumber_c"].ToString()).Substring(lengthnumber, 4));
                              nomor_urut = nomor_urut + 1;
                              no_string = nomor_urut.ToString();
                           if (nomor_urut < 10)
                              //000(9) atau 000(1)
                              number = number + "000" + no_string;
                           else if (nomor_urut > 9 && nomor_urut < 100)
                              //00(10) atau 00(99)
                              number = number + "00" + no_string;
                           else if (nomor_urut > 99 && nomor_urut < 1000)
                              //0(100) atau 0(999)
                              number = number + "0" + no_string;
                           else
                              number = number + no_string + no_string;
                         }else{
                           number = number + "0001";
                         }
                           ttInvcHead_Recs["SPS_LegalNumber_c"] = number;
                        }
                     }else if (ttInvcHead_Recs.Plant == "JKT"){
                        CreateLN = "RT.JKT";
                        DateTime dt = Convert.ToDateTime(ttInvcHead_Recs.InvoiceDate);            
                        cYear = Convert.ToString(dt.Year);
                        cMonth = dt.Date.ToString("MM");
                        cDay = dt.Date.ToString("dd");   
                        number = CreateLN  + "/"+ cYear + "." + cMonth + "." + cDay + "/" ;
                        lengthnumber = number.Length;
                        //ttInvcHead_Recs["SPS_LegalNumber_c"] = number;
                        Erp.Tables.InvcHead InvcHead; 
                        var InvcHead_Recs = (from InvcHead_Row in Db.InvcHead where InvcHead_Row.Company == Session.CompanyID && InvcHead_Row.SPS_LegalNumber_c != "" && InvcHead_Row.InvoiceType ==         ttInvcHead_Recs.InvoiceType && InvcHead_Row.SPS_LegalNumber_c.StartsWith(number)  orderby InvcHead_Row.SPS_LegalNumber_c descending select InvcHead_Row).FirstOrDefault();
                        {
                           var InvcHead_Row = InvcHead_Recs;
                           if (InvcHead_Recs != null){
                              nomor_urut = Convert.ToInt32((InvcHead_Row["SPS_LegalNumber_c"].ToString()).Substring(lengthnumber, 4));
                              nomor_urut = nomor_urut + 1;
                              no_string = nomor_urut.ToString();
                           if (nomor_urut < 10)
                              //000(9) atau 000(1)
                              number = number + "000" + no_string;
                           else if (nomor_urut > 9 && nomor_urut < 100)
                              //00(10) atau 00(99)
                              number = number + "00" + no_string;
                           else if (nomor_urut > 99 && nomor_urut < 1000)
                              //0(100) atau 0(999)
                              number = number + "0" + no_string;
                           else
                              number = number + no_string + no_string;
                         }else{
                           number = number + "0001";
                         }
                           ttInvcHead_Recs["SPS_LegalNumber_c"] = number;
                        }
                     }else if (ttInvcHead_Recs.Plant == "NGW"){
                        CreateLN = "RT.NGW";
                        DateTime dt = Convert.ToDateTime(ttInvcHead_Recs.InvoiceDate);            
                        cYear = Convert.ToString(dt.Year);
                        cMonth = dt.Date.ToString("MM");
                        cDay = dt.Date.ToString("dd");   
                        number = CreateLN  + "/"+ cYear + "." + cMonth + "." + cDay + "/" ;
                        lengthnumber = number.Length;
                        //ttInvcHead_Recs["SPS_LegalNumber_c"] = number;
                        Erp.Tables.InvcHead InvcHead; 
                        var InvcHead_Recs = (from InvcHead_Row in Db.InvcHead where InvcHead_Row.Company == Session.CompanyID && InvcHead_Row.SPS_LegalNumber_c != "" && InvcHead_Row.InvoiceType == ttInvcHead_Recs.InvoiceType && InvcHead_Row.SPS_LegalNumber_c.StartsWith(number)  orderby InvcHead_Row.SPS_LegalNumber_c descending select InvcHead_Row).FirstOrDefault();
                        {
                           var InvcHead_Row = InvcHead_Recs;
                           if (InvcHead_Recs != null){
                              nomor_urut = Convert.ToInt32((InvcHead_Row["SPS_LegalNumber_c"].ToString()).Substring(lengthnumber, 4));
                              nomor_urut = nomor_urut + 1;
                              no_string = nomor_urut.ToString();
                           if (nomor_urut < 10)
                              //000(9) atau 000(1)
                              number = number + "000" + no_string;
                           else if (nomor_urut > 9 && nomor_urut < 100)
                              //00(10) atau 00(99)
                              number = number + "00" + no_string;
                           else if (nomor_urut > 99 && nomor_urut < 1000)
                              //0(100) atau 0(999)
                              number = number + "0" + no_string;
                           else
                              number = number + no_string + no_string;
                         }else{
                           number = number + "0001";
                         }
                           ttInvcHead_Recs["SPS_LegalNumber_c"] = number;
                        }
                     }else if (ttInvcHead_Recs.Plant == "SNG"){
                        CreateLN = "RT.SNG";
                        DateTime dt = Convert.ToDateTime(ttInvcHead_Recs.InvoiceDate);            
                        cYear = Convert.ToString(dt.Year);
                        cMonth = dt.Date.ToString("MM");
                        cDay = dt.Date.ToString("dd");   
                        number = CreateLN  + "/"+ cYear + "." + cMonth + "." + cDay + "/" ;
                        lengthnumber = number.Length;
                        //ttInvcHead_Recs["SPS_LegalNumber_c"] = number;
                        Erp.Tables.InvcHead InvcHead; 
                        var InvcHead_Recs = (from InvcHead_Row in Db.InvcHead where InvcHead_Row.Company == Session.CompanyID && InvcHead_Row.SPS_LegalNumber_c != "" && InvcHead_Row.InvoiceType ==         ttInvcHead_Recs.InvoiceType && InvcHead_Row.SPS_LegalNumber_c.StartsWith(number)  orderby InvcHead_Row.SPS_LegalNumber_c descending select InvcHead_Row).FirstOrDefault();
                        {
                           var InvcHead_Row = InvcHead_Recs;
                           if (InvcHead_Recs != null){
                              nomor_urut = Convert.ToInt32((InvcHead_Row["SPS_LegalNumber_c"].ToString()).Substring(lengthnumber, 4));
                              nomor_urut = nomor_urut + 1;
                              no_string = nomor_urut.ToString();
                           if (nomor_urut < 10)
                              //000(9) atau 000(1)
                              number = number + "000" + no_string;
                           else if (nomor_urut > 9 && nomor_urut < 100)
                              //00(10) atau 00(99)
                              number = number + "00" + no_string;
                           else if (nomor_urut > 99 && nomor_urut < 1000)
                              //0(100) atau 0(999)
                              number = number + "0" + no_string;
                           else
                              number = number + no_string + no_string;
                         }else{
                           number = number + "0001";
                         }
                           ttInvcHead_Recs["SPS_LegalNumber_c"] = number;
                        }
                     }else if (ttInvcHead_Recs.Plant == "DPS"){
                        CreateLN = "RT.DPS";
                        DateTime dt = Convert.ToDateTime(ttInvcHead_Recs.InvoiceDate);            
                        cYear = Convert.ToString(dt.Year);
                        cMonth = dt.Date.ToString("MM");
                        cDay = dt.Date.ToString("dd");   
                        number = CreateLN  + "/"+ cYear + "." + cMonth + "." + cDay + "/" ;
                        lengthnumber = number.Length;
                        //ttInvcHead_Recs["SPS_LegalNumber_c"] = number;
                        Erp.Tables.InvcHead InvcHead; 
                        var InvcHead_Recs = (from InvcHead_Row in Db.InvcHead where InvcHead_Row.Company == Session.CompanyID && InvcHead_Row.SPS_LegalNumber_c != "" && InvcHead_Row.InvoiceType ==         ttInvcHead_Recs.InvoiceType && InvcHead_Row.SPS_LegalNumber_c.StartsWith(number)  orderby InvcHead_Row.SPS_LegalNumber_c descending select InvcHead_Row).FirstOrDefault();
                        {
                           var InvcHead_Row = InvcHead_Recs;
                           if (InvcHead_Recs != null){
                              nomor_urut = Convert.ToInt32((InvcHead_Row["SPS_LegalNumber_c"].ToString()).Substring(lengthnumber, 4));
                              nomor_urut = nomor_urut + 1;
                              no_string = nomor_urut.ToString();
                           if (nomor_urut < 10)
                              //000(9) atau 000(1)
                              number = number + "000" + no_string;
                           else if (nomor_urut > 9 && nomor_urut < 100)
                              //00(10) atau 00(99)
                              number = number + "00" + no_string;
                           else if (nomor_urut > 99 && nomor_urut < 1000)
                              //0(100) atau 0(999)
                              number = number + "0" + no_string;
                           else
                              number = number + no_string + no_string;
                         }else{
                           number = number + "0001";
                         }
                           ttInvcHead_Recs["SPS_LegalNumber_c"] = number;
                        }
                     }else if (ttInvcHead_Recs.Plant == "SMG"){
                        CreateLN = "RT.SMG";
                        DateTime dt = Convert.ToDateTime(ttInvcHead_Recs.InvoiceDate);            
                        cYear = Convert.ToString(dt.Year);
                        cMonth = dt.Date.ToString("MM");
                        cDay = dt.Date.ToString("dd");   
                        number = CreateLN  + "/"+ cYear + "." + cMonth + "." + cDay + "/" ;
                        lengthnumber = number.Length;
                        //ttInvcHead_Recs["SPS_LegalNumber_c"] = number;
                        Erp.Tables.InvcHead InvcHead; 
                        var InvcHead_Recs = (from InvcHead_Row in Db.InvcHead where InvcHead_Row.Company == Session.CompanyID && InvcHead_Row.SPS_LegalNumber_c != "" && InvcHead_Row.InvoiceType ==         ttInvcHead_Recs.InvoiceType && InvcHead_Row.SPS_LegalNumber_c.StartsWith(number)  orderby InvcHead_Row.SPS_LegalNumber_c descending select InvcHead_Row).FirstOrDefault();
                        {
                           var InvcHead_Row = InvcHead_Recs;
                           if (InvcHead_Recs != null){
                              nomor_urut = Convert.ToInt32((InvcHead_Row["SPS_LegalNumber_c"].ToString()).Substring(lengthnumber, 4));
                              nomor_urut = nomor_urut + 1;
                              no_string = nomor_urut.ToString();
                           if (nomor_urut < 10)
                              //000(9) atau 000(1)
                              number = number + "000" + no_string;
                           else if (nomor_urut > 9 && nomor_urut < 100)
                              //00(10) atau 00(99)
                              number = number + "00" + no_string;
                           else if (nomor_urut > 99 && nomor_urut < 1000)
                              //0(100) atau 0(999)
                              number = number + "0" + no_string;
                           else
                              number = number + no_string + no_string;
                         }else{
                           number = number + "0001";
                         }
                           ttInvcHead_Recs["SPS_LegalNumber_c"] = number;
                        }
                     }else{
                        CreateLN = "RT.KDR";
                        DateTime dt = Convert.ToDateTime(ttInvcHead_Recs.InvoiceDate);            
                        cYear = Convert.ToString(dt.Year);
                        cMonth = dt.Date.ToString("MM");
                        cDay = dt.Date.ToString("dd");   
                        number = CreateLN  + "/"+ cYear + "." + cMonth + "." + cDay + "/" ;
                        lengthnumber = number.Length;
                        //ttInvcHead_Recs["SPS_LegalNumber_c"] = number;
                        Erp.Tables.InvcHead InvcHead; 
                        var InvcHead_Recs = (from InvcHead_Row in Db.InvcHead where InvcHead_Row.Company == Session.CompanyID && InvcHead_Row.SPS_LegalNumber_c != "" && InvcHead_Row.InvoiceType ==         ttInvcHead_Recs.InvoiceType && InvcHead_Row.SPS_LegalNumber_c.StartsWith(number)  orderby InvcHead_Row.SPS_LegalNumber_c descending select InvcHead_Row).FirstOrDefault();
                        {
                           var InvcHead_Row = InvcHead_Recs;
                           if (InvcHead_Recs != null){
                              nomor_urut = Convert.ToInt32((InvcHead_Row["SPS_LegalNumber_c"].ToString()).Substring(lengthnumber, 4));
                              nomor_urut = nomor_urut + 1;
                              no_string = nomor_urut.ToString();
                           if (nomor_urut < 10)
                              //000(9) atau 000(1)
                              number = number + "000" + no_string;
                           else if (nomor_urut > 9 && nomor_urut < 100)
                              //00(10) atau 00(99)
                              number = number + "00" + no_string;
                           else if (nomor_urut > 99 && nomor_urut < 1000)
                              //0(100) atau 0(999)
                              number = number + "0" + no_string;
                           else
                              number = number + no_string + no_string;
                         }else{
                           number = number + "0001";
                         }
                           ttInvcHead_Recs["SPS_LegalNumber_c"] = number;
                        }
                     }   
                  }else if(ttInvcHead_Recs.RMANum == 0 && 
                     Convert.ToBoolean(ttInvcHead_Recs.CreditMemo) == true && 
                     Convert.ToBoolean(ttInvcHead_Recs["PTI_Shipment_c"]) == false && 
                     Convert.ToBoolean(ttInvcHead_Recs.CorrectionInv) ==  true){
                     //CR.
                     if(ttInvcHead_Recs.Plant == "BDG"){
                        //GET LN
                        CreateLN = "CR.BDG";
                        //RT.BDG.0001
                        DateTime dt = Convert.ToDateTime(ttInvcHead_Recs.InvoiceDate);            
                        cYear = Convert.ToString(dt.Year);
                        cMonth = dt.Date.ToString("MM");
                        cDay = dt.Date.ToString("dd");   
                        number = CreateLN  + "/"+ cYear + "." + cMonth + "." + cDay + "/" ;
                        lengthnumber = number.Length;
                        //ttInvcHead_Recs["SPS_LegalNumber_c"] = number;
                        Erp.Tables.InvcHead InvcHead; 
                        var InvcHead_Recs = (from InvcHead_Row in Db.InvcHead where InvcHead_Row.Company == Session.CompanyID && InvcHead_Row.SPS_LegalNumber_c != "" && InvcHead_Row.InvoiceType ==         ttInvcHead_Recs.InvoiceType && InvcHead_Row.SPS_LegalNumber_c.StartsWith(number)  orderby InvcHead_Row.SPS_LegalNumber_c descending select InvcHead_Row).FirstOrDefault();
                        {
                           var InvcHead_Row = InvcHead_Recs;
                           if (InvcHead_Recs != null){
                              nomor_urut = Convert.ToInt32((InvcHead_Row["SPS_LegalNumber_c"].ToString()).Substring(lengthnumber, 4));
                              nomor_urut = nomor_urut + 1;
                              no_string = nomor_urut.ToString();
                           if (nomor_urut < 10)
                              //000(9) atau 000(1)
                              number = number + "000" + no_string;
                           else if (nomor_urut > 9 && nomor_urut < 100)
                              //00(10) atau 00(99)
                              number = number + "00" + no_string;
                           else if (nomor_urut > 99 && nomor_urut < 1000)
                              //0(100) atau 0(999)
                              number = number + "0" + no_string;
                           else
                              number = number + no_string + no_string;
                         }else{
                           number = number + "0001";
                         }
                           ttInvcHead_Recs["SPS_LegalNumber_c"] = number;
                        }
                     }else if (ttInvcHead_Recs.Plant == "JKT"){
                        CreateLN = "CR.JKT";
                        DateTime dt = Convert.ToDateTime(ttInvcHead_Recs.InvoiceDate);            
                        cYear = Convert.ToString(dt.Year);
                        cMonth = dt.Date.ToString("MM");
                        cDay = dt.Date.ToString("dd");   
                        number = CreateLN  + "/"+ cYear + "." + cMonth + "." + cDay + "/" ;
                        lengthnumber = number.Length;
                        //ttInvcHead_Recs["SPS_LegalNumber_c"] = number;
                        Erp.Tables.InvcHead InvcHead; 
                        var InvcHead_Recs = (from InvcHead_Row in Db.InvcHead where InvcHead_Row.Company == Session.CompanyID && InvcHead_Row.SPS_LegalNumber_c != "" && InvcHead_Row.InvoiceType ==         ttInvcHead_Recs.InvoiceType && InvcHead_Row.SPS_LegalNumber_c.StartsWith(number)  orderby InvcHead_Row.SPS_LegalNumber_c descending select InvcHead_Row).FirstOrDefault();
                        {
                           var InvcHead_Row = InvcHead_Recs;
                           if (InvcHead_Recs != null){
                              nomor_urut = Convert.ToInt32((InvcHead_Row["SPS_LegalNumber_c"].ToString()).Substring(lengthnumber, 4));
                              nomor_urut = nomor_urut + 1;
                              no_string = nomor_urut.ToString();
                           if (nomor_urut < 10)
                              //000(9) atau 000(1)
                              number = number + "000" + no_string;
                           else if (nomor_urut > 9 && nomor_urut < 100)
                              //00(10) atau 00(99)
                              number = number + "00" + no_string;
                           else if (nomor_urut > 99 && nomor_urut < 1000)
                              //0(100) atau 0(999)
                              number = number + "0" + no_string;
                           else
                              number = number + no_string + no_string;
                         }else{
                           number = number + "0001";
                         }
                           ttInvcHead_Recs["SPS_LegalNumber_c"] = number;
                        }
                     }else if (ttInvcHead_Recs.Plant == "NGW"){
                        CreateLN = "CR.NGW";
                        DateTime dt = Convert.ToDateTime(ttInvcHead_Recs.InvoiceDate);            
                        cYear = Convert.ToString(dt.Year);
                        cMonth = dt.Date.ToString("MM");
                        cDay = dt.Date.ToString("dd");   
                        number = CreateLN  + "/"+ cYear + "." + cMonth + "." + cDay + "/" ;
                        lengthnumber = number.Length;
                        //ttInvcHead_Recs["SPS_LegalNumber_c"] = number;
                        Erp.Tables.InvcHead InvcHead; 
                        var InvcHead_Recs = (from InvcHead_Row in Db.InvcHead where InvcHead_Row.Company == Session.CompanyID && InvcHead_Row.SPS_LegalNumber_c != "" && InvcHead_Row.InvoiceType == ttInvcHead_Recs.InvoiceType && InvcHead_Row.SPS_LegalNumber_c.StartsWith(number)  orderby InvcHead_Row.SPS_LegalNumber_c descending select InvcHead_Row).FirstOrDefault();
                        {
                           var InvcHead_Row = InvcHead_Recs;
                           if (InvcHead_Recs != null){
                              nomor_urut = Convert.ToInt32((InvcHead_Row["SPS_LegalNumber_c"].ToString()).Substring(lengthnumber, 4));
                              nomor_urut = nomor_urut + 1;
                              no_string = nomor_urut.ToString();
                           if (nomor_urut < 10)
                              //000(9) atau 000(1)
                              number = number + "000" + no_string;
                           else if (nomor_urut > 9 && nomor_urut < 100)
                              //00(10) atau 00(99)
                              number = number + "00" + no_string;
                           else if (nomor_urut > 99 && nomor_urut < 1000)
                              //0(100) atau 0(999)
                              number = number + "0" + no_string;
                           else
                              number = number + no_string + no_string;
                         }else{
                           number = number + "0001";
                         }
                           ttInvcHead_Recs["SPS_LegalNumber_c"] = number;
                        }
                     }else if (ttInvcHead_Recs.Plant == "SNG"){
                        CreateLN = "CR.SNG";
                        DateTime dt = Convert.ToDateTime(ttInvcHead_Recs.InvoiceDate);            
                        cYear = Convert.ToString(dt.Year);
                        cMonth = dt.Date.ToString("MM");
                        cDay = dt.Date.ToString("dd");   
                        number = CreateLN  + "/"+ cYear + "." + cMonth + "." + cDay + "/" ;
                        lengthnumber = number.Length;
                        //ttInvcHead_Recs["SPS_LegalNumber_c"] = number;
                        Erp.Tables.InvcHead InvcHead; 
                        var InvcHead_Recs = (from InvcHead_Row in Db.InvcHead where InvcHead_Row.Company == Session.CompanyID && InvcHead_Row.SPS_LegalNumber_c != "" && InvcHead_Row.InvoiceType ==         ttInvcHead_Recs.InvoiceType && InvcHead_Row.SPS_LegalNumber_c.StartsWith(number)  orderby InvcHead_Row.SPS_LegalNumber_c descending select InvcHead_Row).FirstOrDefault();
                        {
                           var InvcHead_Row = InvcHead_Recs;
                           if (InvcHead_Recs != null){
                              nomor_urut = Convert.ToInt32((InvcHead_Row["SPS_LegalNumber_c"].ToString()).Substring(lengthnumber, 4));
                              nomor_urut = nomor_urut + 1;
                              no_string = nomor_urut.ToString();
                           if (nomor_urut < 10)
                              //000(9) atau 000(1)
                              number = number + "000" + no_string;
                           else if (nomor_urut > 9 && nomor_urut < 100)
                              //00(10) atau 00(99)
                              number = number + "00" + no_string;
                           else if (nomor_urut > 99 && nomor_urut < 1000)
                              //0(100) atau 0(999)
                              number = number + "0" + no_string;
                           else
                              number = number + no_string + no_string;
                         }else{
                           number = number + "0001";
                         }
                           ttInvcHead_Recs["SPS_LegalNumber_c"] = number;
                        }
                     }else if (ttInvcHead_Recs.Plant == "DPS"){
                        CreateLN = "CR.DPS";
                        DateTime dt = Convert.ToDateTime(ttInvcHead_Recs.InvoiceDate);            
                        cYear = Convert.ToString(dt.Year);
                        cMonth = dt.Date.ToString("MM");
                        cDay = dt.Date.ToString("dd");   
                        number = CreateLN  + "/"+ cYear + "." + cMonth + "." + cDay + "/" ;
                        lengthnumber = number.Length;
                        //ttInvcHead_Recs["SPS_LegalNumber_c"] = number;
                        Erp.Tables.InvcHead InvcHead; 
                        var InvcHead_Recs = (from InvcHead_Row in Db.InvcHead where InvcHead_Row.Company == Session.CompanyID && InvcHead_Row.SPS_LegalNumber_c != "" && InvcHead_Row.InvoiceType ==         ttInvcHead_Recs.InvoiceType && InvcHead_Row.SPS_LegalNumber_c.StartsWith(number)  orderby InvcHead_Row.SPS_LegalNumber_c descending select InvcHead_Row).FirstOrDefault();
                        {
                           var InvcHead_Row = InvcHead_Recs;
                           if (InvcHead_Recs != null){
                              nomor_urut = Convert.ToInt32((InvcHead_Row["SPS_LegalNumber_c"].ToString()).Substring(lengthnumber, 4));
                              nomor_urut = nomor_urut + 1;
                              no_string = nomor_urut.ToString();
                           if (nomor_urut < 10)
                              //000(9) atau 000(1)
                              number = number + "000" + no_string;
                           else if (nomor_urut > 9 && nomor_urut < 100)
                              //00(10) atau 00(99)
                              number = number + "00" + no_string;
                           else if (nomor_urut > 99 && nomor_urut < 1000)
                              //0(100) atau 0(999)
                              number = number + "0" + no_string;
                           else
                              number = number + no_string + no_string;
                         }else{
                           number = number + "0001";
                         }
                           ttInvcHead_Recs["SPS_LegalNumber_c"] = number;
                        }
                     }else if (ttInvcHead_Recs.Plant == "SMG"){
                        CreateLN = "CR.SMG";
                        DateTime dt = Convert.ToDateTime(ttInvcHead_Recs.InvoiceDate);            
                        cYear = Convert.ToString(dt.Year);
                        cMonth = dt.Date.ToString("MM");
                        cDay = dt.Date.ToString("dd");   
                        number = CreateLN  + "/"+ cYear + "." + cMonth + "." + cDay + "/" ;
                        lengthnumber = number.Length;
                        //ttInvcHead_Recs["SPS_LegalNumber_c"] = number;
                        Erp.Tables.InvcHead InvcHead; 
                        var InvcHead_Recs = (from InvcHead_Row in Db.InvcHead where InvcHead_Row.Company == Session.CompanyID && InvcHead_Row.SPS_LegalNumber_c != "" && InvcHead_Row.InvoiceType ==         ttInvcHead_Recs.InvoiceType && InvcHead_Row.SPS_LegalNumber_c.StartsWith(number)  orderby InvcHead_Row.SPS_LegalNumber_c descending select InvcHead_Row).FirstOrDefault();
                        {
                           var InvcHead_Row = InvcHead_Recs;
                           if (InvcHead_Recs != null){
                              nomor_urut = Convert.ToInt32((InvcHead_Row["SPS_LegalNumber_c"].ToString()).Substring(lengthnumber, 4));
                              nomor_urut = nomor_urut + 1;
                              no_string = nomor_urut.ToString();
                           if (nomor_urut < 10)
                              //000(9) atau 000(1)
                              number = number + "000" + no_string;
                           else if (nomor_urut > 9 && nomor_urut < 100)
                              //00(10) atau 00(99)
                              number = number + "00" + no_string;
                           else if (nomor_urut > 99 && nomor_urut < 1000)
                              //0(100) atau 0(999)
                              number = number + "0" + no_string;
                           else
                              number = number + no_string + no_string;
                         }else{
                           number = number + "0001";
                         }
                           ttInvcHead_Recs["SPS_LegalNumber_c"] = number;
                        }
                     }else{
                        CreateLN = "CR.KDR";
                        DateTime dt = Convert.ToDateTime(ttInvcHead_Recs.InvoiceDate);            
                        cYear = Convert.ToString(dt.Year);
                        cMonth = dt.Date.ToString("MM");
                        cDay = dt.Date.ToString("dd");   
                        number = CreateLN  + "/"+ cYear + "." + cMonth + "." + cDay + "/" ;
                        lengthnumber = number.Length;
                        //ttInvcHead_Recs["SPS_LegalNumber_c"] = number;
                        Erp.Tables.InvcHead InvcHead; 
                        var InvcHead_Recs = (from InvcHead_Row in Db.InvcHead where InvcHead_Row.Company == Session.CompanyID && InvcHead_Row.SPS_LegalNumber_c != "" && InvcHead_Row.InvoiceType ==         ttInvcHead_Recs.InvoiceType && InvcHead_Row.SPS_LegalNumber_c.StartsWith(number)  orderby InvcHead_Row.SPS_LegalNumber_c descending select InvcHead_Row).FirstOrDefault();
                        {
                           var InvcHead_Row = InvcHead_Recs;
                           if (InvcHead_Recs != null){
                              nomor_urut = Convert.ToInt32((InvcHead_Row["SPS_LegalNumber_c"].ToString()).Substring(lengthnumber, 4));
                              nomor_urut = nomor_urut + 1;
                              no_string = nomor_urut.ToString();
                           if (nomor_urut < 10)
                              //000(9) atau 000(1)
                              number = number + "000" + no_string;
                           else if (nomor_urut > 9 && nomor_urut < 100)
                              //00(10) atau 00(99)
                              number = number + "00" + no_string;
                           else if (nomor_urut > 99 && nomor_urut < 1000)
                              //0(100) atau 0(999)
                              number = number + "0" + no_string;
                           else
                              number = number + no_string + no_string;
                         }else{
                           number = number + "0001";
                         }
                           ttInvcHead_Recs["SPS_LegalNumber_c"] = number;
                        }
                     }                  
                  }else if(ttInvcHead_Recs.RMANum == 0 && 
                     Convert.ToBoolean(ttInvcHead_Recs.CreditMemo) == false && 
                     Convert.ToBoolean(ttInvcHead_Recs["PTI_Shipment_c"]) == false && 
                     Convert.ToBoolean(ttInvcHead_Recs.CorrectionInv) ==  false){
                     //MIS.
                     if(ttInvcHead_Recs.Plant == "BDG"){
                        //GET LN
                        CreateLN = "MIS.BDG";
                        //RT.BDG.0001
                        DateTime dt = Convert.ToDateTime(ttInvcHead_Recs.InvoiceDate);            
                        cYear = Convert.ToString(dt.Year);
                        cMonth = dt.Date.ToString("MM");
                        cDay = dt.Date.ToString("dd");   
                        number = CreateLN  + "/"+ cYear + "." + cMonth + "." + cDay + "/" ;
                        lengthnumber = number.Length;
                        //ttInvcHead_Recs["SPS_LegalNumber_c"] = number;
                        Erp.Tables.InvcHead InvcHead; 
                        var InvcHead_Recs = (from InvcHead_Row in Db.InvcHead where InvcHead_Row.Company == Session.CompanyID && InvcHead_Row.SPS_LegalNumber_c != "" && InvcHead_Row.InvoiceType ==         ttInvcHead_Recs.InvoiceType && InvcHead_Row.SPS_LegalNumber_c.StartsWith(number)  orderby InvcHead_Row.SPS_LegalNumber_c descending select InvcHead_Row).FirstOrDefault();
                        {
                           var InvcHead_Row = InvcHead_Recs;
                           if (InvcHead_Recs != null){
                              nomor_urut = Convert.ToInt32((InvcHead_Row["SPS_LegalNumber_c"].ToString()).Substring(lengthnumber, 4));
                              nomor_urut = nomor_urut + 1;
                              no_string = nomor_urut.ToString();
                           if (nomor_urut < 10)
                              //000(9) atau 000(1)
                              number = number + "000" + no_string;
                           else if (nomor_urut > 9 && nomor_urut < 100)
                              //00(10) atau 00(99)
                              number = number + "00" + no_string;
                           else if (nomor_urut > 99 && nomor_urut < 1000)
                              //0(100) atau 0(999)
                              number = number + "0" + no_string;
                           else
                              number = number + no_string + no_string;
                         }else{
                           number = number + "0001";
                         }
                           ttInvcHead_Recs["SPS_LegalNumber_c"] = number;
                        }
                     }else if (ttInvcHead_Recs.Plant == "JKT"){
                        CreateLN = "MIS.JKT";
                        DateTime dt = Convert.ToDateTime(ttInvcHead_Recs.InvoiceDate);            
                        cYear = Convert.ToString(dt.Year);
                        cMonth = dt.Date.ToString("MM");
                        cDay = dt.Date.ToString("dd");   
                        number = CreateLN  + "/"+ cYear + "." + cMonth + "." + cDay + "/" ;
                        lengthnumber = number.Length;
                        //ttInvcHead_Recs["SPS_LegalNumber_c"] = number;
                        Erp.Tables.InvcHead InvcHead; 
                        var InvcHead_Recs = (from InvcHead_Row in Db.InvcHead where InvcHead_Row.Company == Session.CompanyID && InvcHead_Row.SPS_LegalNumber_c != "" && InvcHead_Row.InvoiceType ==         ttInvcHead_Recs.InvoiceType && InvcHead_Row.SPS_LegalNumber_c.StartsWith(number)  orderby InvcHead_Row.SPS_LegalNumber_c descending select InvcHead_Row).FirstOrDefault();
                        {
                           var InvcHead_Row = InvcHead_Recs;
                           if (InvcHead_Recs != null){
                              nomor_urut = Convert.ToInt32((InvcHead_Row["SPS_LegalNumber_c"].ToString()).Substring(lengthnumber, 4));
                              nomor_urut = nomor_urut + 1;
                              no_string = nomor_urut.ToString();
                           if (nomor_urut < 10)
                              //000(9) atau 000(1)
                              number = number + "000" + no_string;
                           else if (nomor_urut > 9 && nomor_urut < 100)
                              //00(10) atau 00(99)
                              number = number + "00" + no_string;
                           else if (nomor_urut > 99 && nomor_urut < 1000)
                              //0(100) atau 0(999)
                              number = number + "0" + no_string;
                           else
                              number = number + no_string + no_string;
                         }else{
                           number = number + "0001";
                         }
                           ttInvcHead_Recs["SPS_LegalNumber_c"] = number;
                        }
                     }else if (ttInvcHead_Recs.Plant == "NGW"){
                        CreateLN = "MIS.NGW";
                        DateTime dt = Convert.ToDateTime(ttInvcHead_Recs.InvoiceDate);            
                        cYear = Convert.ToString(dt.Year);
                        cMonth = dt.Date.ToString("MM");
                        cDay = dt.Date.ToString("dd");   
                        number = CreateLN  + "/"+ cYear + "." + cMonth + "." + cDay + "/" ;
                        lengthnumber = number.Length;
                        //ttInvcHead_Recs["SPS_LegalNumber_c"] = number;
                        Erp.Tables.InvcHead InvcHead; 
                        var InvcHead_Recs = (from InvcHead_Row in Db.InvcHead where InvcHead_Row.Company == Session.CompanyID && InvcHead_Row.SPS_LegalNumber_c != "" && InvcHead_Row.InvoiceType == ttInvcHead_Recs.InvoiceType && InvcHead_Row.SPS_LegalNumber_c.StartsWith(number)  orderby InvcHead_Row.SPS_LegalNumber_c descending select InvcHead_Row).FirstOrDefault();
                        {
                           var InvcHead_Row = InvcHead_Recs;
                           if (InvcHead_Recs != null){
                              nomor_urut = Convert.ToInt32((InvcHead_Row["SPS_LegalNumber_c"].ToString()).Substring(lengthnumber, 4));
                              nomor_urut = nomor_urut + 1;
                              no_string = nomor_urut.ToString();
                           if (nomor_urut < 10)
                              //000(9) atau 000(1)
                              number = number + "000" + no_string;
                           else if (nomor_urut > 9 && nomor_urut < 100)
                              //00(10) atau 00(99)
                              number = number + "00" + no_string;
                           else if (nomor_urut > 99 && nomor_urut < 1000)
                              //0(100) atau 0(999)
                              number = number + "0" + no_string;
                           else
                              number = number + no_string + no_string;
                         }else{
                           number = number + "0001";
                         }
                           ttInvcHead_Recs["SPS_LegalNumber_c"] = number;
                        }
                     }else if (ttInvcHead_Recs.Plant == "SNG"){
                        CreateLN = "MIS.SNG";
                        DateTime dt = Convert.ToDateTime(ttInvcHead_Recs.InvoiceDate);            
                        cYear = Convert.ToString(dt.Year);
                        cMonth = dt.Date.ToString("MM");
                        cDay = dt.Date.ToString("dd");   
                        number = CreateLN  + "/"+ cYear + "." + cMonth + "." + cDay + "/" ;
                        lengthnumber = number.Length;
                        //ttInvcHead_Recs["SPS_LegalNumber_c"] = number;
                        Erp.Tables.InvcHead InvcHead; 
                        var InvcHead_Recs = (from InvcHead_Row in Db.InvcHead where InvcHead_Row.Company == Session.CompanyID && InvcHead_Row.SPS_LegalNumber_c != "" && InvcHead_Row.InvoiceType ==         ttInvcHead_Recs.InvoiceType && InvcHead_Row.SPS_LegalNumber_c.StartsWith(number)  orderby InvcHead_Row.SPS_LegalNumber_c descending select InvcHead_Row).FirstOrDefault();
                        {
                           var InvcHead_Row = InvcHead_Recs;
                           if (InvcHead_Recs != null){
                              nomor_urut = Convert.ToInt32((InvcHead_Row["SPS_LegalNumber_c"].ToString()).Substring(lengthnumber, 4));
                              nomor_urut = nomor_urut + 1;
                              no_string = nomor_urut.ToString();
                           if (nomor_urut < 10)
                              //000(9) atau 000(1)
                              number = number + "000" + no_string;
                           else if (nomor_urut > 9 && nomor_urut < 100)
                              //00(10) atau 00(99)
                              number = number + "00" + no_string;
                           else if (nomor_urut > 99 && nomor_urut < 1000)
                              //0(100) atau 0(999)
                              number = number + "0" + no_string;
                           else
                              number = number + no_string + no_string;
                         }else{
                           number = number + "0001";
                         }
                           ttInvcHead_Recs["SPS_LegalNumber_c"] = number;
                        }
                     }else if (ttInvcHead_Recs.Plant == "DPS"){
                        CreateLN = "MIS.DPS";
                        DateTime dt = Convert.ToDateTime(ttInvcHead_Recs.InvoiceDate);            
                        cYear = Convert.ToString(dt.Year);
                        cMonth = dt.Date.ToString("MM");
                        cDay = dt.Date.ToString("dd");   
                        number = CreateLN  + "/"+ cYear + "." + cMonth + "." + cDay + "/" ;
                        lengthnumber = number.Length;
                        //ttInvcHead_Recs["SPS_LegalNumber_c"] = number;
                        Erp.Tables.InvcHead InvcHead; 
                        var InvcHead_Recs = (from InvcHead_Row in Db.InvcHead where InvcHead_Row.Company == Session.CompanyID && InvcHead_Row.SPS_LegalNumber_c != "" && InvcHead_Row.InvoiceType ==         ttInvcHead_Recs.InvoiceType && InvcHead_Row.SPS_LegalNumber_c.StartsWith(number)  orderby InvcHead_Row.SPS_LegalNumber_c descending select InvcHead_Row).FirstOrDefault();
                        {
                           var InvcHead_Row = InvcHead_Recs;
                           if (InvcHead_Recs != null){
                              nomor_urut = Convert.ToInt32((InvcHead_Row["SPS_LegalNumber_c"].ToString()).Substring(lengthnumber, 4));
                              nomor_urut = nomor_urut + 1;
                              no_string = nomor_urut.ToString();
                           if (nomor_urut < 10)
                              //000(9) atau 000(1)
                              number = number + "000" + no_string;
                           else if (nomor_urut > 9 && nomor_urut < 100)
                              //00(10) atau 00(99)
                              number = number + "00" + no_string;
                           else if (nomor_urut > 99 && nomor_urut < 1000)
                              //0(100) atau 0(999)
                              number = number + "0" + no_string;
                           else
                              number = number + no_string + no_string;
                         }else{
                           number = number + "0001";
                         }
                           ttInvcHead_Recs["SPS_LegalNumber_c"] = number;
                        }
                     }else if (ttInvcHead_Recs.Plant == "SMG"){
                        CreateLN = "MIS.SMG";
                        DateTime dt = Convert.ToDateTime(ttInvcHead_Recs.InvoiceDate);            
                        cYear = Convert.ToString(dt.Year);
                        cMonth = dt.Date.ToString("MM");
                        cDay = dt.Date.ToString("dd");   
                        number = CreateLN  + "/"+ cYear + "." + cMonth + "." + cDay + "/" ;
                        lengthnumber = number.Length;
                        //ttInvcHead_Recs["SPS_LegalNumber_c"] = number;
                        Erp.Tables.InvcHead InvcHead; 
                        var InvcHead_Recs = (from InvcHead_Row in Db.InvcHead where InvcHead_Row.Company == Session.CompanyID && InvcHead_Row.SPS_LegalNumber_c != "" && InvcHead_Row.InvoiceType ==         ttInvcHead_Recs.InvoiceType && InvcHead_Row.SPS_LegalNumber_c.StartsWith(number)  orderby InvcHead_Row.SPS_LegalNumber_c descending select InvcHead_Row).FirstOrDefault();
                        {
                           var InvcHead_Row = InvcHead_Recs;
                           if (InvcHead_Recs != null){
                              nomor_urut = Convert.ToInt32((InvcHead_Row["SPS_LegalNumber_c"].ToString()).Substring(lengthnumber, 4));
                              nomor_urut = nomor_urut + 1;
                              no_string = nomor_urut.ToString();
                           if (nomor_urut < 10)
                              //000(9) atau 000(1)
                              number = number + "000" + no_string;
                           else if (nomor_urut > 9 && nomor_urut < 100)
                              //00(10) atau 00(99)
                              number = number + "00" + no_string;
                           else if (nomor_urut > 99 && nomor_urut < 1000)
                              //0(100) atau 0(999)
                              number = number + "0" + no_string;
                           else
                              number = number + no_string + no_string;
                         }else{
                           number = number + "0001";
                         }
                           ttInvcHead_Recs["SPS_LegalNumber_c"] = number;
                        }
                     }else{
                        CreateLN = "MIS.KDR";
                        DateTime dt = Convert.ToDateTime(ttInvcHead_Recs.InvoiceDate);            
                        cYear = Convert.ToString(dt.Year);
                        cMonth = dt.Date.ToString("MM");
                        cDay = dt.Date.ToString("dd");   
                        number = CreateLN  + "/"+ cYear + "." + cMonth + "." + cDay + "/" ;
                        lengthnumber = number.Length;
                        //ttInvcHead_Recs["SPS_LegalNumber_c"] = number;
                        Erp.Tables.InvcHead InvcHead; 
                        var InvcHead_Recs = (from InvcHead_Row in Db.InvcHead where InvcHead_Row.Company == Session.CompanyID && InvcHead_Row.SPS_LegalNumber_c != "" && InvcHead_Row.InvoiceType ==         ttInvcHead_Recs.InvoiceType && InvcHead_Row.SPS_LegalNumber_c.StartsWith(number)  orderby InvcHead_Row.SPS_LegalNumber_c descending select InvcHead_Row).FirstOrDefault();
                        {
                           var InvcHead_Row = InvcHead_Recs;
                           if (InvcHead_Recs != null){
                              nomor_urut = Convert.ToInt32((InvcHead_Row["SPS_LegalNumber_c"].ToString()).Substring(lengthnumber, 4));
                              nomor_urut = nomor_urut + 1;
                              no_string = nomor_urut.ToString();
                           if (nomor_urut < 10)
                              //000(9) atau 000(1)
                              number = number + "000" + no_string;
                           else if (nomor_urut > 9 && nomor_urut < 100)
                              //00(10) atau 00(99)
                              number = number + "00" + no_string;
                           else if (nomor_urut > 99 && nomor_urut < 1000)
                              //0(100) atau 0(999)
                              number = number + "0" + no_string;
                           else
                              number = number + no_string + no_string;
                         }else{
                           number = number + "0001";
                         }
                           ttInvcHead_Recs["SPS_LegalNumber_c"] = number;
                        }
                     }
                  }else if(ttInvcHead_Recs.RMANum == 0 && 
                     Convert.ToBoolean(ttInvcHead_Recs.CreditMemo) == true && 
                     Convert.ToBoolean(ttInvcHead_Recs["PTI_Shipment_c"]) == false && 
                     Convert.ToBoolean(ttInvcHead_Recs.CorrectionInv) ==  false){
                     //MIS.
                     if(ttInvcHead_Recs.Plant == "BDG"){
                        //GET LN
                        CreateLN = "RT.BDG";
                        //RT.BDG.0001
                        DateTime dt = Convert.ToDateTime(ttInvcHead_Recs.InvoiceDate);            
                        cYear = Convert.ToString(dt.Year);
                        cMonth = dt.Date.ToString("MM");
                        cDay = dt.Date.ToString("dd");   
                        number = CreateLN  + "/"+ cYear + "." + cMonth + "." + cDay + "/" ;
                        lengthnumber = number.Length;
                        //ttInvcHead_Recs["SPS_LegalNumber_c"] = number;
                        Erp.Tables.InvcHead InvcHead; 
                        var InvcHead_Recs = (from InvcHead_Row in Db.InvcHead where InvcHead_Row.Company == Session.CompanyID && InvcHead_Row.SPS_LegalNumber_c != "" && InvcHead_Row.InvoiceType ==         ttInvcHead_Recs.InvoiceType && InvcHead_Row.SPS_LegalNumber_c.StartsWith(number)  orderby InvcHead_Row.SPS_LegalNumber_c descending select InvcHead_Row).FirstOrDefault();
                        {
                           var InvcHead_Row = InvcHead_Recs;
                           if (InvcHead_Recs != null){
                              nomor_urut = Convert.ToInt32((InvcHead_Row["SPS_LegalNumber_c"].ToString()).Substring(lengthnumber, 4));
                              nomor_urut = nomor_urut + 1;
                              no_string = nomor_urut.ToString();
                           if (nomor_urut < 10)
                              //000(9) atau 000(1)
                              number = number + "000" + no_string;
                           else if (nomor_urut > 9 && nomor_urut < 100)
                              //00(10) atau 00(99)
                              number = number + "00" + no_string;
                           else if (nomor_urut > 99 && nomor_urut < 1000)
                              //0(100) atau 0(999)
                              number = number + "0" + no_string;
                           else
                              number = number + no_string + no_string;
                         }else{
                           number = number + "0001";
                         }
                           ttInvcHead_Recs["SPS_LegalNumber_c"] = number;
                        }
                     }else if (ttInvcHead_Recs.Plant == "JKT"){
                        CreateLN = "RT.JKT";
                        DateTime dt = Convert.ToDateTime(ttInvcHead_Recs.InvoiceDate);            
                        cYear = Convert.ToString(dt.Year);
                        cMonth = dt.Date.ToString("MM");
                        cDay = dt.Date.ToString("dd");   
                        number = CreateLN  + "/"+ cYear + "." + cMonth + "." + cDay + "/" ;
                        lengthnumber = number.Length;
                        //ttInvcHead_Recs["SPS_LegalNumber_c"] = number;
                        Erp.Tables.InvcHead InvcHead; 
                        var InvcHead_Recs = (from InvcHead_Row in Db.InvcHead where InvcHead_Row.Company == Session.CompanyID && InvcHead_Row.SPS_LegalNumber_c != "" && InvcHead_Row.InvoiceType ==         ttInvcHead_Recs.InvoiceType && InvcHead_Row.SPS_LegalNumber_c.StartsWith(number)  orderby InvcHead_Row.SPS_LegalNumber_c descending select InvcHead_Row).FirstOrDefault();
                        {
                           var InvcHead_Row = InvcHead_Recs;
                           if (InvcHead_Recs != null){
                              nomor_urut = Convert.ToInt32((InvcHead_Row["SPS_LegalNumber_c"].ToString()).Substring(lengthnumber, 4));
                              nomor_urut = nomor_urut + 1;
                              no_string = nomor_urut.ToString();
                           if (nomor_urut < 10)
                              //000(9) atau 000(1)
                              number = number + "000" + no_string;
                           else if (nomor_urut > 9 && nomor_urut < 100)
                              //00(10) atau 00(99)
                              number = number + "00" + no_string;
                           else if (nomor_urut > 99 && nomor_urut < 1000)
                              //0(100) atau 0(999)
                              number = number + "0" + no_string;
                           else
                              number = number + no_string + no_string;
                         }else{
                           number = number + "0001";
                         }
                           ttInvcHead_Recs["SPS_LegalNumber_c"] = number;
                        }
                     }else if (ttInvcHead_Recs.Plant == "NGW"){
                        CreateLN = "RT.NGW";
                        DateTime dt = Convert.ToDateTime(ttInvcHead_Recs.InvoiceDate);            
                        cYear = Convert.ToString(dt.Year);
                        cMonth = dt.Date.ToString("MM");
                        cDay = dt.Date.ToString("dd");   
                        number = CreateLN  + "/"+ cYear + "." + cMonth + "." + cDay + "/" ;
                        lengthnumber = number.Length;
                        //ttInvcHead_Recs["SPS_LegalNumber_c"] = number;
                        Erp.Tables.InvcHead InvcHead; 
                        var InvcHead_Recs = (from InvcHead_Row in Db.InvcHead where InvcHead_Row.Company == Session.CompanyID && InvcHead_Row.SPS_LegalNumber_c != "" && InvcHead_Row.InvoiceType == ttInvcHead_Recs.InvoiceType && InvcHead_Row.SPS_LegalNumber_c.StartsWith(number)  orderby InvcHead_Row.SPS_LegalNumber_c descending select InvcHead_Row).FirstOrDefault();
                        {
                           var InvcHead_Row = InvcHead_Recs;
                           if (InvcHead_Recs != null){
                              nomor_urut = Convert.ToInt32((InvcHead_Row["SPS_LegalNumber_c"].ToString()).Substring(lengthnumber, 4));
                              nomor_urut = nomor_urut + 1;
                              no_string = nomor_urut.ToString();
                           if (nomor_urut < 10)
                              //000(9) atau 000(1)
                              number = number + "000" + no_string;
                           else if (nomor_urut > 9 && nomor_urut < 100)
                              //00(10) atau 00(99)
                              number = number + "00" + no_string;
                           else if (nomor_urut > 99 && nomor_urut < 1000)
                              //0(100) atau 0(999)
                              number = number + "0" + no_string;
                           else
                              number = number + no_string + no_string;
                         }else{
                           number = number + "0001";
                         }
                           ttInvcHead_Recs["SPS_LegalNumber_c"] = number;
                        }
                     }else if (ttInvcHead_Recs.Plant == "SNG"){
                        CreateLN = "RT.SNG";
                        DateTime dt = Convert.ToDateTime(ttInvcHead_Recs.InvoiceDate);            
                        cYear = Convert.ToString(dt.Year);
                        cMonth = dt.Date.ToString("MM");
                        cDay = dt.Date.ToString("dd");   
                        number = CreateLN  + "/"+ cYear + "." + cMonth + "." + cDay + "/" ;
                        lengthnumber = number.Length;
                        //ttInvcHead_Recs["SPS_LegalNumber_c"] = number;
                        Erp.Tables.InvcHead InvcHead; 
                        var InvcHead_Recs = (from InvcHead_Row in Db.InvcHead where InvcHead_Row.Company == Session.CompanyID && InvcHead_Row.SPS_LegalNumber_c != "" && InvcHead_Row.InvoiceType ==         ttInvcHead_Recs.InvoiceType && InvcHead_Row.SPS_LegalNumber_c.StartsWith(number)  orderby InvcHead_Row.SPS_LegalNumber_c descending select InvcHead_Row).FirstOrDefault();
                        {
                           var InvcHead_Row = InvcHead_Recs;
                           if (InvcHead_Recs != null){
                              nomor_urut = Convert.ToInt32((InvcHead_Row["SPS_LegalNumber_c"].ToString()).Substring(lengthnumber, 4));
                              nomor_urut = nomor_urut + 1;
                              no_string = nomor_urut.ToString();
                           if (nomor_urut < 10)
                              //000(9) atau 000(1)
                              number = number + "000" + no_string;
                           else if (nomor_urut > 9 && nomor_urut < 100)
                              //00(10) atau 00(99)
                              number = number + "00" + no_string;
                           else if (nomor_urut > 99 && nomor_urut < 1000)
                              //0(100) atau 0(999)
                              number = number + "0" + no_string;
                           else
                              number = number + no_string + no_string;
                         }else{
                           number = number + "0001";
                         }
                           ttInvcHead_Recs["SPS_LegalNumber_c"] = number;
                        }
                     }else if (ttInvcHead_Recs.Plant == "DPS"){
                        CreateLN = "RT.DPS";
                        DateTime dt = Convert.ToDateTime(ttInvcHead_Recs.InvoiceDate);            
                        cYear = Convert.ToString(dt.Year);
                        cMonth = dt.Date.ToString("MM");
                        cDay = dt.Date.ToString("dd");   
                        number = CreateLN  + "/"+ cYear + "." + cMonth + "." + cDay + "/" ;
                        lengthnumber = number.Length;
                        //ttInvcHead_Recs["SPS_LegalNumber_c"] = number;
                        Erp.Tables.InvcHead InvcHead; 
                        var InvcHead_Recs = (from InvcHead_Row in Db.InvcHead where InvcHead_Row.Company == Session.CompanyID && InvcHead_Row.SPS_LegalNumber_c != "" && InvcHead_Row.InvoiceType ==         ttInvcHead_Recs.InvoiceType && InvcHead_Row.SPS_LegalNumber_c.StartsWith(number)  orderby InvcHead_Row.SPS_LegalNumber_c descending select InvcHead_Row).FirstOrDefault();
                        {
                           var InvcHead_Row = InvcHead_Recs;
                           if (InvcHead_Recs != null){
                              nomor_urut = Convert.ToInt32((InvcHead_Row["SPS_LegalNumber_c"].ToString()).Substring(lengthnumber, 4));
                              nomor_urut = nomor_urut + 1;
                              no_string = nomor_urut.ToString();
                           if (nomor_urut < 10)
                              //000(9) atau 000(1)
                              number = number + "000" + no_string;
                           else if (nomor_urut > 9 && nomor_urut < 100)
                              //00(10) atau 00(99)
                              number = number + "00" + no_string;
                           else if (nomor_urut > 99 && nomor_urut < 1000)
                              //0(100) atau 0(999)
                              number = number + "0" + no_string;
                           else
                              number = number + no_string + no_string;
                         }else{
                           number = number + "0001";
                         }
                           ttInvcHead_Recs["SPS_LegalNumber_c"] = number;
                        }
                     }else if (ttInvcHead_Recs.Plant == "SMG"){
                        CreateLN = "RT.SMG";
                        DateTime dt = Convert.ToDateTime(ttInvcHead_Recs.InvoiceDate);            
                        cYear = Convert.ToString(dt.Year);
                        cMonth = dt.Date.ToString("MM");
                        cDay = dt.Date.ToString("dd");   
                        number = CreateLN  + "/"+ cYear + "." + cMonth + "." + cDay + "/" ;
                        lengthnumber = number.Length;
                        //ttInvcHead_Recs["SPS_LegalNumber_c"] = number;
                        Erp.Tables.InvcHead InvcHead; 
                        var InvcHead_Recs = (from InvcHead_Row in Db.InvcHead where InvcHead_Row.Company == Session.CompanyID && InvcHead_Row.SPS_LegalNumber_c != "" && InvcHead_Row.InvoiceType ==         ttInvcHead_Recs.InvoiceType && InvcHead_Row.SPS_LegalNumber_c.StartsWith(number)  orderby InvcHead_Row.SPS_LegalNumber_c descending select InvcHead_Row).FirstOrDefault();
                        {
                           var InvcHead_Row = InvcHead_Recs;
                           if (InvcHead_Recs != null){
                              nomor_urut = Convert.ToInt32((InvcHead_Row["SPS_LegalNumber_c"].ToString()).Substring(lengthnumber, 4));
                              nomor_urut = nomor_urut + 1;
                              no_string = nomor_urut.ToString();
                           if (nomor_urut < 10)
                              //000(9) atau 000(1)
                              number = number + "000" + no_string;
                           else if (nomor_urut > 9 && nomor_urut < 100)
                              //00(10) atau 00(99)
                              number = number + "00" + no_string;
                           else if (nomor_urut > 99 && nomor_urut < 1000)
                              //0(100) atau 0(999)
                              number = number + "0" + no_string;
                           else
                              number = number + no_string + no_string;
                         }else{
                           number = number + "0001";
                         }
                           ttInvcHead_Recs["SPS_LegalNumber_c"] = number;
                        }
                     }else{
                        CreateLN = "RT.KDR";
                        DateTime dt = Convert.ToDateTime(ttInvcHead_Recs.InvoiceDate);            
                        cYear = Convert.ToString(dt.Year);
                        cMonth = dt.Date.ToString("MM");
                        cDay = dt.Date.ToString("dd");   
                        number = CreateLN  + "/"+ cYear + "." + cMonth + "." + cDay + "/" ;
                        lengthnumber = number.Length;
                        //ttInvcHead_Recs["SPS_LegalNumber_c"] = number;
                        Erp.Tables.InvcHead InvcHead; 
                        var InvcHead_Recs = (from InvcHead_Row in Db.InvcHead where InvcHead_Row.Company == Session.CompanyID && InvcHead_Row.SPS_LegalNumber_c != "" && InvcHead_Row.InvoiceType ==         ttInvcHead_Recs.InvoiceType && InvcHead_Row.SPS_LegalNumber_c.StartsWith(number)  orderby InvcHead_Row.SPS_LegalNumber_c descending select InvcHead_Row).FirstOrDefault();
                        {
                           var InvcHead_Row = InvcHead_Recs;
                           if (InvcHead_Recs != null){
                              nomor_urut = Convert.ToInt32((InvcHead_Row["SPS_LegalNumber_c"].ToString()).Substring(lengthnumber, 4));
                              nomor_urut = nomor_urut + 1;
                              no_string = nomor_urut.ToString();
                           if (nomor_urut < 10)
                              //000(9) atau 000(1)
                              number = number + "000" + no_string;
                           else if (nomor_urut > 9 && nomor_urut < 100)
                              //00(10) atau 00(99)
                              number = number + "00" + no_string;
                           else if (nomor_urut > 99 && nomor_urut < 1000)
                              //0(100) atau 0(999)
                              number = number + "0" + no_string;
                           else
                              number = number + no_string + no_string;
                         }else{
                           number = number + "0001";
                         }
                           ttInvcHead_Recs["SPS_LegalNumber_c"] = number;
                        }
                     }
                  }else{
                     CreateLN = "Legal Number Belum Teridentifikasi";
                     this.PublishInfoMessage(CreateLN, Ice.Common.BusinessObjectMessageType.Information, Ice.Bpm.InfoMessageDisplayMode.Individual, "FirstVar","SecondVar");
                  }
               }
            }
         } 
      }
}