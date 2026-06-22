program KalkulatorLengkap;

var
  a, b, hasil: real;
  pilihan: integer;

begin
  repeat
    writeln('======================');
    writeln('   KALKULATOR PASCAL  ');
    writeln('======================');
    writeln('1. Penjumlahan');
    writeln('2. Pengurangan');
    writeln('3. Perkalian');
    writeln('4. Pembagian');
    writeln('5. Keluar');
    writeln('----------------------');
    write('Pilih menu (1-5): ');
    readln(pilihan);

    if (pilihan >= 1) and (pilihan <= 4) then
    begin
      write('Masukkan angka pertama: ');
      readln(a);
      write('Masukkan angka kedua: ');
      readln(b);
    end;

    case pilihan of
      1: begin
           hasil := a + b;
           writeln('Hasil: ', hasil:0:2);
         end;

      2: begin
           hasil := a - b;
           writeln('Hasil: ', hasil:0:2);
         end;

      3: begin
           hasil := a * b;
           writeln('Hasil: ', hasil:0:2);
         end;

      4: begin
           if b <> 0 then
             writeln('Hasil: ', (a / b):0:2)
           else
             writeln('Error: Pembagian dengan nol!');
         end;

      5: writeln('Keluar dari program...');

    else
      writeln('Pilihan tidak valid!');
    end;

    writeln;

  until pilihan = 5;
end.
