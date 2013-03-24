import java.math.BigInteger;
import java.util.Random;
import java.io.*;
import java.util.zip.*;

public class JCripto11{
	GCriptoZ gz;
	
	public JCripto11(int param){
		gz=new GCriptoZ(param);
	}
	
	public JCripto11(){
		gz=new GCriptoZ(5000); //d�gitos por default
	}

	public void ayuda(){
		System.out.println("JCripto 1.0 (2005)\njava -jar jcripto.jar\n\t-c <archivo> <arch-clave> |\n\t-d <archivo> <arch-clave> | "+
		  "\n\t-f <archivo> |\n\t-g <cant-digitos> <nombre-arch> | "+
			"\n\t-v <firma> <archivo-descifrado> |\n\t-z <archivo1> <archivo2> |\n\t-u <archivo> |\n\t-cmp <archivo1> <archivo2> |\n\t-ayuda\n"+
			"-c --> cifrado de <archivo> utilizando la clave <arch-clave> \n\t --- genera <archivo>.cif)\n"+
			"-d --> descifrado de <archivo> utilizando la clave <arch-clave>\n\t--- genera <archivo> sin la extensi�n \".cif\"\n"+
			"-f --> firmado de <archivo>\n\t--- (genera <archivo>.hash)\n"+
			"-g --> genera un par de claves de <cant-d�gitos>\n\t--- en dos archivos ( <nombre-arch>.pri y <nombre-arch>.pub)\n"+
			"-v --> verifica la firma <firma> para el archivo <archivo-descifrado>\n"+
			"-z --> comprime <archivo1> y <archivo2> como <archivo1>.zip\n"+
			"-u --> descomprime <archivo> en los archivos originales\n\t--- 2 archivos necesariamente\n"+
			"-cmp --> compara <archivo1> con <archivo2> a nivel de bits");
	}
	
	public void cmp(String a1, String a2){
    DataInputStream f1, f2;
    byte b1, b2;
    int i=0;
    
    try{
      f1=new DataInputStream(new FileInputStream(a1));
      f2=new DataInputStream(new FileInputStream(a2));
      
      while ((f1.available()>0)&&(f2.available()>0)){
        b1=f1.readByte();
        b2=f2.readByte();
        
        if (b1!=b2) System.out.println(i+" ---> B1= "+b1+" <> B2= "+b2);
        i++;
      }
      if (f1.available()!=f2.available()){
        System.out.println("Longitud de archivos desigual!");
        System.out.println("Diferencia de "+Math.max(f1.available(), f2.available())+" bytes");
      }
    }catch (IOException e){ e.printStackTrace();}
  }

	public static void main(String[] args){
		JCripto11 m=new JCripto11();
/**
		par�metros
		java JCripto11 -c <archivo> <arch-clave>|
			-d <archivo> <arch-clave> | 
			-f <archivo> | 
			-g <cant-digitos> <nombre-arch> |
			-v <firma> <archivo-descifrado> |
		  -z <archivo1> <archivo2> |
			-u <archivo> |
			-cmp <archivo1> <archivo2> |
		 	-ayuda
		
		-c cifrar <archivo> usando <arch-clave>
		-d descifrar <archivo> usando <arch-clave>
		-f firmar <archivo>
		-v verificar firmas entre <firma> y <archivo-descifrado>
		-g generar claves de <cant-digitos> en <nombre-arch>.pri y <nombre-arch>.pub (administrador)
		-z comprime los dos archivos (cifrado <archivo1> y firma <archivo2>) 
		-u descomprime un archivo (comprimido con esta aplicaci�n)
		-cmp compara <archivo1> con <archivo2> a nivel de bits
		-ayuda 
		*/
		if ((args.length<2)||(args[1].equals("-ayuda"))) m.ayuda();			
		else if ((args[0].equalsIgnoreCase("-c"))&&(args.length==3)){
			System.out.println("Comenzando el cifrado...");
			m.gz.codificar(args[1], args[2]);
			System.out.println("Cifrado terminado.-");
		}else if ((args[0].equalsIgnoreCase("-d"))&&(args.length==3)){
			System.out.println("Comenzando el descifrado...");
			m.gz.decodificar(args[1], args[2]);
			System.out.println("Descifrado terminado.-");
		}else if ((args[0].equalsIgnoreCase("-f"))&&(args.length==2)){
			System.out.println("Comenzando el firmado...");
			m.gz.firmar(args[1]);
			System.out.println("Firmado terminado.-");
		}else if ((args[0].equalsIgnoreCase("-v"))&&(args.length==3)){
			System.out.println("Comenzando el verificado de firmas...");
			if (m.gz.chequearFirma(args[1], args[2]))
				System.out.println("Firmas coincidentes.-");
			else System.out.println("LAS FIRMAS NO COINCIDEN!!!");
			System.out.println("Verificado de firmas terminado.-");
		}else if ((args[0].equalsIgnoreCase("-g"))&&(args.length==3)){
			System.out.println("Generando claves...");
  		m.gz=new GCriptoZ(Integer.parseInt(args[1]));
	  	m.gz.generarClaves(args[2]);
		  System.out.println("Generaci\u00f3n terminada.-");
		  m.gz.showStats();
		}else if ((args[0].equalsIgnoreCase("-z"))&&(args.length==3)){
			System.out.println("Comenzando el empaquetado...");
			m.gz.comprimir(args[1], args[2]);
			System.out.println("Empaquetado terminado.-");
		}else if ((args[0].equalsIgnoreCase("-u"))&&(args.length==2)){
			System.out.println("Comenzando el desempaquetado...");
			m.gz.descomprimir(args[1]);
			System.out.println("Desempaquetado terminado.-");
		}else if ((args[0].equalsIgnoreCase("-cmp"))&&(args.length==2)){
			System.out.println("Comenzando el comparado a nivel de bits...");
			m.cmp(args[1], args[2]);
			System.out.println("Comparado terminado.-");
		}else m.ayuda();
	}
}
//////////////////////////////////////////////////////////////////////////////
class GCriptoZ extends GCripto10{

  public GCriptoZ(int digitos){
	  super(digitos);
  }

  /////////////////////////////////////////////////////////////	
  //METODOS DE ACCESO A LAS FUNCIONES DE GCRIPTO
  /////////////////////////////////////////////////////////////	
  //public void codificar(String file, String clave)
  //public void decodificar(String file, String clave)
  //public void firmar(String fname)
  //public boolean chequearFirma(String doc, String firma)
  //public void generarClaves(String fname)
  /////////////////////////////////////////////////////////////
  public void comprimir(String fname, String fhash){
  	ZipOutputStream targetStream;
  	BufferedInputStream sourceStream;
	  ZipEntry entrada;
	  byte[] data;
  	int bCnt;
  
	  try{
	    targetStream = new ZipOutputStream(new FileOutputStream(fname+".zip"));
	    targetStream.setMethod(ZipOutputStream.DEFLATED);
	    targetStream.setLevel(9);
	    
	    sourceStream = new BufferedInputStream(new FileInputStream(fname));
	    entrada = new ZipEntry(fname);
	    targetStream.putNextEntry(entrada);
	    data = new byte[100];
	    while ((bCnt = sourceStream.read(data, 0, 100)) != -1){
		    targetStream.write(data, 0, bCnt);
	    }
	    targetStream.flush();
	    sourceStream.close();
	    
	    sourceStream = new BufferedInputStream(new FileInputStream(fhash));
	    entrada = new ZipEntry(fhash);
	    targetStream.putNextEntry(entrada);
	    data = new byte[100];
	    while ((bCnt = sourceStream.read(data, 0, 100)) != -1){
    		targetStream.write(data, 0, bCnt);
	    }
	    targetStream.flush();
	    sourceStream.close();
	    
	    targetStream.closeEntry();
	    targetStream.close();
  	}catch(IOException e){
	    System.out.println("ERROR en el proceso de compresi\u00f3n de los archivos.\ncode=DEF\n"+e.getMessage());
	  }
  }

  public void descomprimir(String fname){
	  ZipInputStream source;
  	ZipEntry theEntry;
	  BufferedOutputStream targetStream;
  	int byteCount;
	  byte[] data=new byte[100];;

	  try{
	    source = new ZipInputStream(new FileInputStream(fname));
	    while ((theEntry = source.getNextEntry()) != null ){
		    targetStream = new BufferedOutputStream(new FileOutputStream(theEntry.getName()));
    		while ((byteCount = source.read(data, 0, 100)) != -1){
		      targetStream.write(data, 0, byteCount);
    		}
    		targetStream.flush();
	    	targetStream.close();
	    }
	    source.close();
  	}catch(IOException e){
	    System.out.println("ERROR en el proceso de descompresi\00f3n de los archivos.\ncode=INF\n"+e.getMessage());
  	}
  }
}
//////////////////////////////////////////////////////////////////////////////////////////
class GCripto10{
  public static final int ERROR_OK=0;/** Indica que todo est� bien.*/
  public static final int ERROR_FNF=-10; /** Archivo no encontrado.*/
  public static final int ERROR_EOF=-20; /** Fin de archivo (no esperado).*/
  public static final int ERROR_ANOTHER_KEY=-30; /** Utilizaci\u00f3n incorrecta de las claves.*/
  public static final int ERROR_ENTRADA=-40; /** Entrada inv\u00e1lida.*/
  public static final int ERROR_ENTRADA_CLAVE_PUBLICA=-41; /** Clave p\u00fablica con error/es.*/
  public static final int ERROR_ENTRADA_CLAVE_PRIVADA=-42; /** Clave privada con error/es.*/
  public static final int ERROR_ENTRADA_ARCHIVO_DATOS=-43; /** Archivo de datos con error/es.*/
  public static final int ERROR_ENTRADA_ARCHIVO_CIFRADO=-44; /** Archivo cifrado con error/es.*/
  public static final int ERROR_SALIDA=-50; /** Salida inv\u00e1lida.*/
  public static final int ERROR_SALIDA_CLAVE_PUBLICA=-51; /** Problemas al escribir la clave p\u00fablica.*/
  public static final int ERROR_SALIDA_CLAVE_PRIVADA=-52; /** Problemas al escribir la clave privada.*/
  public static final int ERROR_SALIDA_ARCHIVO_DATOS=-53; /** Problemas al descifrar (error/es al escribir la salida).*/
  public static final int ERROR_SALIDA_ARCHIVO_CIFRADO=-54; /** Problemas al escribir el archivo cifrado.*/
  public static final int ERROR_SALIDA_CLAVES_CERRANDO=-55; /** Problemas al cerrar los archivos de claves.*/
  public static final int ERROR_ENTRADA_SALIDA=-60; /** Error indeterminado de entrada/salida.*/
  public static final int ERROR_DESCONOCIDO=-100; /** Error no detectado y clasificado (BUG).*/

  private BigInteger x1, x2, fx1, fx2, rango, maximo, error;
  private int digits, equals;
/////////////////////////////////////////////////////////////	
  /** 
  	El par\u00e1metro requerido es la cantidad de d\u00edgitos (binarios) que se desean en la clave.
  */
  public GCripto10(int digits){
	  this.digits=digits*3;
	  this.digits+=this.digits%8;
  	rango=BigInteger.ZERO;
	  rango=rango.setBit(this.digits);
  	maximo=(rango.multiply(BigInteger.valueOf((long)2))).divide(BigInteger.valueOf((long)3));
  }
/////////////////////////////////////////////////////////////	
  private BigInteger getX1(){ return x1;}
  private BigInteger getX2(){ return x2;}
  private BigInteger getRango(){ return rango;}
/////////////////////////////////////////////////////////////	
  private BigInteger g(BigInteger x){
    return (x.pow(2)).multiply(rango.subtract(x));
  }
    
  private BigInteger g(BigInteger x, BigInteger rango){
  	return (x.pow(2)).multiply(rango.subtract(x));
  }
/////////////////////////////////////////////////////////////
  /**
     Muestra un mensaje de error con los detalles del suceso
  */
  public void error(int error, String msg){
    System.out.println(error);
  }
  /*public void error(int error, String msg){
	//������������
	//\u00e1\u00e9\u00ed\u00f3\u00fa\u00f1\u00d1\u00c1\u00c9\u00cd\u00d3\u00da
  	System.out.println("EXCEPTION:\n"+msg+"\nERROR (detalle):\n");
	  switch (error){
  	case ERROR_FNF:
	      System.out.println("Archivo no encontrado.");break;
  	case ERROR_EOF:
	    System.out.println("Fin de archivo no esperado.");break;
	  case ERROR_ANOTHER_KEY:
	    System.out.println("Se intenta utilizar una clave que no corresponde.");break;
  	case ERROR_ENTRADA:
	    System.out.println("Problemas de entrada con uno de los archivos.");break;
	  case ERROR_ENTRADA_CLAVE_PUBLICA:
	    System.out.println("Problemas de entrada en el archivo de clave p\u00fablica.");break;
  	case ERROR_ENTRADA_CLAVE_PRIVADA:
	    System.out.println("Problemas de entrada en el archivo de clave privada.");break;
	  case ERROR_ENTRADA_ARCHIVO_DATOS:
	    System.out.println("Problemas de entrada en el archivo a cifrar.");break;
  	case ERROR_ENTRADA_ARCHIVO_CIFRADO:
	    System.out.println("Problemas de entrada en el archivo a descifrar.");break;
  	case ERROR_SALIDA:
	    System.out.println("Problemas de salida en uno los archivos.");break;
	  case ERROR_SALIDA_CLAVE_PUBLICA:
	    System.out.println("Problemas de salida en el archivo de clave p\u00fablica.");break;
  	case ERROR_SALIDA_CLAVE_PRIVADA:
	    System.out.println("Problemas de salida en el archivo de clave privada.");break;
	  case ERROR_SALIDA_ARCHIVO_DATOS:
	    System.out.println("Problemas de salida en el archivo descifrado.");break;
  	case ERROR_SALIDA_ARCHIVO_CIFRADO:
	    System.out.println("Problemas de salida en el archivo cifrado.");break;
	  case ERROR_SALIDA_CLAVES_CERRANDO:
	    System.out.println("Problemas en el cerrado de los archivos de claves.");break;
  	case ERROR_ENTRADA_SALIDA:
	    System.out.println("Problemas con el/los archivo/s de entrada y/o salida.");break;
	  case ERROR_DESCONOCIDO:
	    System.out.println("BUG!!! Reportar a \"gaudina@unsl.edu.ar\"");break;
   	case ERROR_OK:
	    System.out.println("Operaci\u00f3n exitosa!");
	  }
  }*/
/////////////////////////////////////////////////////////////
  private void genKeyPair(String fkey){
  	BigInteger li=new BigInteger("0"), ls=maximo;
	  DataOutputStream outpub, outpri;
	
	  do{
	    x2=(new BigInteger(digits-1, new Random())).add(maximo);
  	}while (x2.compareTo(rango)>0);
	  fx2=g(x2);
  	x1=(li.add(ls)).divide(BigInteger.valueOf((long)2));
	  fx1=g(x1);
  	while (((ls.subtract(li)).abs()).compareTo(BigInteger.valueOf((long)1))>0){
	    if (fx1.compareTo(fx2)<0) li=x1;				
	    else ls=x1;
	    x1=(li.add(ls)).divide(BigInteger.valueOf((long)2));
	    fx1=g(x1);
	  }
  	fx1=g(x1);
	  String sfx1=fx1.toString(16), sfx2=fx2.toString(16);
  	for (equals=0; (equals<sfx1.length())&&(sfx1.charAt(equals)==sfx2.charAt(equals)); equals++);
	  error=fx2.subtract(fx1);
  	try{
	    outpub=new DataOutputStream(new FileOutputStream(fkey+".pub"));
	    outpri=new DataOutputStream(new FileOutputStream(fkey+".pri"));

	    //clave publica
	    byte[] b=x1.toByteArray();
	    try{
	    	outpub.writeBoolean(true);
    		outpub.writeInt(b.length);	    
		    outpub.write(b, 0, b.length);
		    outpub.writeInt(digits);
	    }catch(IOException e){ error(ERROR_SALIDA_CLAVE_PUBLICA, "code=GKPU\n"+e.getMessage());}
	    //clave privada
	    try{
    		outpri.writeBoolean(false);
	    	outpri.writeInt(digits);
		    b=error.toByteArray();
    		outpri.writeInt(b.length);
	    	outpri.write(b, 0, b.length);
		    b=x2.toByteArray();
    		outpri.writeInt(b.length);
		    outpri.write(b, 0, b.length);
	    }catch(IOException e){ error(ERROR_SALIDA_CLAVE_PRIVADA, "code=GKPR\n"+e.getMessage());}
	    
	    outpub.close();
	    outpri.close();
  	}catch (IOException e){ error(ERROR_SALIDA_CLAVES_CERRANDO, "code=GKP\n"+e.getMessage());}
  }
/////////////////////////////////////////////////////////////
  private BigInteger getPrivCode(DataInputStream key){//extrae la clave privada de un archivo .pri
  	BigInteger error, rango;
	  byte[] b;

  	try{//cabecera
	    rango=BigInteger.ZERO;
	    rango=rango.setBit(key.readInt());//cantidad de d�gitos
	    b=new byte[key.readInt()];
	    key.read(b, 0, b.length);
	    error=new BigInteger(b);//diferencia entre las claves
	    b=new byte[key.readInt()];
	    key.read(b, 0, b.length);//x1
	    return g(new BigInteger(b), rango).subtract(error);//(g(x1)=fx1)-error = fx2
  	}catch(IOException e){ error(ERROR_ENTRADA_CLAVE_PRIVADA, "code=GPR\n"+e.getMessage());}
	  return null;
  }

  private BigInteger getPubCode(DataInputStream key){//extrae la clave p�blica de un archivo .pub
	  byte[] b;
	  BigInteger rango;

  	try{//cabecera
	    b=new byte[key.readInt()];//tama�o de x2
	    key.read(b, 0, b.length);//lee x2
	    rango=BigInteger.ZERO;
	    rango=rango.setBit(key.readInt());//seteado del bit que indica el rango
	    return g(new BigInteger(b), rango);//g(x2, rango)=fx2
	  }catch(IOException e){ error(ERROR_ENTRADA_CLAVE_PUBLICA, "code=GPU\n"+e.getMessage());}
  	return null;
  }

  private void convert(BigInteger code, boolean sign, DataInputStream in, DataOutputStream out){
	  int digits=code.toByteArray().length, orig=0, i;
    BigInteger data;
	  byte b, cd[];

	  cd=code.toByteArray();
	  try{
      i=0;
 	  	while (in.available()>0){
   	    out.writeByte(in.readByte()^cd[i%digits]);
   	    i++;
 	  	}
	  }catch(IOException e){ error(ERROR_ENTRADA_SALIDA, "code=CNV\n"+e.getMessage());}
  }
  //cifrado de un archivo de entrada
  private void cifrar(boolean cifrar, String fnamein, String fnameout, String fkey){
  	DataInputStream in;
	  DataInputStream key;
  	DataOutputStream out;
	  boolean k;

	  try{
	    in=new DataInputStream(new FileInputStream(fnamein));//archivo a cifrar
	    key=new DataInputStream(new FileInputStream(fkey));//clave a usar
	    if (cifrar){//cifrar
    		out=new DataOutputStream(new FileOutputStream(fnameout));//creado del archivo de salida cifrado
		    k=key.readBoolean();//parte de la cabecera del archivo de clave
    		out.writeBoolean(k);
    		if (k) convert(getPubCode(key), true, in, out);//cifrado con clave p�blica
    		else convert(getPrivCode(key), true, in, out);//cifrado con clave privada
    		out.close();
	    }else{//descifrar
		    k=key.readBoolean();
    		if (in.readBoolean()==(!k)){
      		out=new DataOutputStream(new FileOutputStream(fnameout));//creado del archivo de salida (original)
  		    if (k) convert(getPubCode(key), false, in, out);//descifrado con clave p�blica
	  	    else convert(getPrivCode(key), false, in, out);//descifrado con clave privada
	  	    out.close();
  	  	}else error(ERROR_ANOTHER_KEY, "code=CFRD\n");//se intenta utilizar una clave que no corresponde
	    }
	    key.close();
	    in.close();
	  }catch(IOException e){ error(ERROR_ENTRADA_SALIDA, "code=CFR\n"+e.getMessage());}
  }
/////////////////////////////////////////////////////////////
  private void hash(String fname){
  	DataInputStream in;
	  DataOutputStream out;
  	BigInteger tmp, hsh=BigInteger.ZERO;
	  byte[] b=new byte[digits];
	
  	try{	
	    in=new DataInputStream(new FileInputStream(fname));
	    while (in.read(b)!=-1){
	  	  tmp=new BigInteger(b);
  		  hsh=hsh.add(tmp);
	    }//b+=b nuevo
	    in.close();
	    out=new DataOutputStream(new FileOutputStream(fname+".hash"));
	    out.writeUTF(hsh.toString(10));
	    out.close();
  	}catch (IOException e){ error(ERROR_ENTRADA_SALIDA, "code=HSH\n"+e.getMessage());}
  }

  private boolean comparar(String fname1, String fname2){
  	DataInputStream in1, in2;
  	boolean flag=true;

	  try{
	    in1=new DataInputStream(new FileInputStream(fname1));
	    in2=new DataInputStream(new FileInputStream(fname2));

	    while ((in1.available()>0)&&(in2.available()>0)){
		    if (in1.readByte()!=in2.readByte()) break;//firmas distintas
	    }
	    if ((in1.available()>0)||(in2.available()>0)) flag=false;//retornar firmas no coincidentes
	    in1.close();
	    in2.close();
	    return flag;
  	}catch(IOException e){ error(ERROR_ENTRADA, "code=CMP\n"+e.getMessage());}
	  return flag;//firmas coincidentes
  }
/////////////////////////////////////////////////////////////	
  /**
     Muestra varios resultados estad\u00edsticos sobre la generaci�n de las claves.
  */
  public void showStats(){
	  System.out.println("Estad\u00edsticas (con "+digits+" d\u00edgitos):\nRango:\n"+rango.toString(10));
  	System.out.println("M\u00e1ximo:\n"+maximo.toString(10));
	  System.out.println("x1:\n"+x1.toString(10)+"\nx2:\n"+x2.toString(10));
  	System.out.println("f(x1) y f(x2):\n"+fx1.toString(10)+"\n"+fx2.toString(10));
	  System.out.println("Error:\n"+((fx2.subtract(fx1)).abs()).toString(10));
  }
    
  ////////////////////////////////////////////////////////////	
  //METODOS DE ACCESO A LAS FUNCIONES DE GCRIPTO
  /////////////////////////////////////////////////////////////	
  /**
     M\u00e9todo de acceso a la funci\u00f3n de cifrado de un archivo "file" utilizando el archivo de clave "clave",
     genera un archivo "file".cif que corresponde al cifrado.
  */
  public void codificar(String file, String clave){
	  cifrar(true, file, file+".cif", clave);
  }
  /**
     M\u00e9todo de acceso a la funci\u00f3n de descifrado de un archivo "file".cif utilizando el archivo de clave "clave",
     genera un archivo "file" que corresponde al descifrado.
  */
  public void decodificar(String file, String clave){
	  cifrar(false, file, file.substring(0, file.lastIndexOf(".")), clave);
  }
  /**
     Obtiene un archivo fname.hash con un resumen del original
  */
  public void firmar(String fname){
	  hash(fname);
  }
  /**
     Verifica la igualdad entre un archivo "doc" (obteniendo su resumen) y lo compara con el archivo "firma" (previamente resumido)
  */
  public boolean chequearFirma(String firma, String doc){
	  boolean rta;

	  (new File(doc)).renameTo(new File("tmp"));
  	hash("tmp");
	  rta=comparar("tmp.hash", firma);
	  (new File("tmp")).renameTo(new File(doc));
	  (new File("tmp.hash")).delete();
	  return rta;
  }
	/**
	  Genenera un par de claves
  */
	public void generarClaves(String fname){
	  genKeyPair(fname);
  }
}