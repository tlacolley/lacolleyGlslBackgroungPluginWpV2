	//based on : https://www.shadertoy.com/view/ltcGDj	

	#ifdef GL_ES
	precision mediump float;
	#endif

	#define TWO_PI 6.28318530718
	// Variables --------------------------------------------
	uniform float u_delta;
	uniform vec2 u_resolution;
	uniform vec2 u_mouse;
	uniform float u_time;

	uniform float u_key;
	uniform float u_blackTransition; 
	uniform float u_easing ;



	uniform sampler2D u_tex0;
	uniform vec2 u_tex0Resolution; 



	uniform sampler2D u_buffer0;

	#define TEXTURED 1

	#ifdef BUFFER_0

			// Definition des Iteration ---------------------------------
			#define ITR 40

			#define FAR 400.

			const vec3 lgt = vec3(-.523, .41, -.747);
			mat2 m2 = mat2( 0.80,  0.60, -0.60,  0.80 );

			//form iq, see: http://www.iquilezles.org/www/articles/morenoise/morenoise.htm
			vec3 noised( in vec2 x )
			{
				vec2 p = floor(x);
				vec2 f = fract(x);
				vec2 u = f*f*(3.0-2.0*f);
				float a = texture2D(u_tex0,(p+vec2(0.5,0.5))/256.0,-100.0).x;
				float b = texture2D(u_tex0,(p+vec2(1.5,0.5))/256.0,-100.0).x;
				float c = texture2D(u_tex0,(p+vec2(0.5,1.5))/256.0,-100.0).x;
				float d = texture2D(u_tex0,(p+vec2(1.5,1.5))/256.0,-100.0).x;
				return vec3(a+(b-a)*u.x+(c-a)*u.y+(a-b-c+d)*u.x*u.y,
							6.0*f*(1.0-f)*(vec2(b-a,c-a)+(a-b-c+d)*u.yx));
			}

			float terrain( in vec2 p)
			{
				float rz = 0.1;
				float z = 1.2;
				vec2  d = vec2(0.1);
				float scl = 3.95;
				float zscl = -.4;
				float zz = 5.;
				for( int i=0; i<5; i++ )
				{
					vec3 n = noised(p);
					d += pow(abs(n.yz),vec2(zz));
					d -= smoothstep(-.5,1.5,n.yz);
					zz -= 1.;
					rz += z*n.x/(dot(d,d)+.85);
					z *= zscl;
					zscl *= .8;
					p = m2*p*scl;
				}
				
				rz /= smoothstep(1.5,-.5,rz)+.75;
				return rz;
			}

			float map(vec3 p)
			{
				//return p.y-(terrain(p.zx*0.07))*2.7-1.;
				return p.y;

			}

			/*	The idea is simple, as the ray gets further from the eye, I increase 
				the step size of the raymarching and lower the target precision, 
				this allows for better performance with virtually no loss in visual quality. */
			float march(in vec3 ro, in vec3 rd, out float itrc)
			{
							
				float t = 0.;
				float d = map(rd*t+ro);
				float precis = 0.0001;
				for (int i=0;i<=ITR;i++)
				{
					if (abs(d) < precis || t > FAR) break;
					precis = t*0.0001;
					float rl = max(t*0.02,1.);
					t += d*rl;
					d = map(rd*t+ro)*0.7;
					itrc++;
				}

				return t;
			}

			vec3 rotx(vec3 p, float a){
				float s = sin(a), c = cos(a);
				return vec3(p.x, c*p.y - s*p.z, s*p.y + c*p.z);
			}

			vec3 roty(vec3 p, float a){
				float s = sin(a), c = cos(a);
				return vec3(c*p.x + s*p.z, p.y, -s*p.x + c*p.z);
			}

			vec3 rotz(vec3 p, float a){
				float s = sin(a), c = cos(a);
				return vec3(c*p.x - s*p.y, s*p.x + c*p.y, p.z);
			}

			vec3 normal(in vec3 p, in float ds)
			{  
				vec2 e = vec2(-1., 1.)*0.0005*pow(ds,1.);
				return normalize(e.yxx*map(p + e.yxx) + e.xxy*map(p + e.xxy) + 
								e.xyx*map(p + e.xyx) + e.yyy*map(p + e.yyy) );   
			}

			float noise(in vec2 x){return texture2D(u_tex0, x*.01).x;}
			// Enplaccement pour afficher la texture2D 
			float fbm(in vec2 p)
			{	
				
				float z= .5;
				float rz = 0.;
				for (float i= 0.;i<3.;i++ )
				{
					rz+= (sin(noise(p)*5.)*0.5+0.5) *z;
					z *= 0.5;
					p = p*2.;
				}
				return rz;
			}

			float bnoise(in vec2 p){ return fbm(p*3.); }
			vec3 bump(in vec3 p, in vec3 n, in float ds)
			{
				
				vec2 e = vec2(0.005*ds,0);
				float n0 = bnoise(p.zx);
				vec3 d = vec3(bnoise(p.zx+e.xy)-n0, 1., bnoise(p.zx+e.yx)-n0)/e.x*0.025;
				d -= n*dot(n,d);
				n = normalize(n-d);
				return n;
			}

			float curv(in vec3 p, in float w)
			{
				
				vec2 e = vec2(-1., 1.)*w;   
				float t1 = map(p + e.yxx), t2 = map(p + e.xxy);
				float t3 = map(p + e.xyx), t4 = map(p + e.yyy);
				return .15/e.y *(t1 + t2 + t3 + t4 - 4. * map(p));
			}

			//Based on: http://www.iquilezles.org/www/articles/fog/fog.htm
			// effect fog de l atmsphere
			vec3 fog(vec3 ro, vec3 rd, vec3 col, float ds)
			{
				
				vec3 pos = ro + rd*ds;
				float mx = (fbm(pos.zx*0.1-u_time*0.05)-0.5)*.2;
				
				const float b= 1.;
				float den = 0.1*exp(-ro.y*b)*(1.0-exp( -ds*rd.y*b ))/rd.y;
				float sdt = max(dot(rd, lgt), 0.);
				vec3  fogColor  = mix(vec3(0.8,0.8,1)*1.2, vec3(1)*1.3, pow(sdt,2.0)+mx*0.5);
				return mix( col, fogColor, clamp(den + mx,0.,1.) );
			}

			float linstep(in float mn, in float mx, in float x){
				return clamp((x - mn)/(mx - mn), 0., 1.);
			}

			//Complete hack, but looks good enough :)
			// Effect atmosphere plus bleu au fond 
			vec3 scatter(vec3 ro, vec3 rd)
			{   
				float sd= max(dot(lgt, rd)*0.5+0.5,0.);
				float dtp = 13.-(ro + rd*(FAR)).y*3.5;
				float hori = (linstep(-1500., 0.0, dtp) - linstep(11., 500., dtp))*1.;
				hori *= pow(sd,.04);
			

				vec3 col = vec3(0);
				// les vec3 de la fin corresponde au couleur de l atmosphere 
				col += pow(hori, 500.)*vec3(0.15, 0.4,  1)*3.;
				col += pow(hori, 200.)*vec3(0.15, 0.4,  1)*2.5;
				col += pow(hori, 25.)* vec3(0.4, 0.5,  1)*.3;
				col += pow(hori, 7.)* vec3(0.6, 0.7,  1)*.8;
				
				return (col);
			}

			//From Dave_Hoskins (https://www.shadertoy.com/view/4djSRW)
			vec3 hash33(vec3 p)
			{
				p = fract(p * vec3(443.8975,397.2973, 491.1871));
				p += dot(p.zxy, p.yxz+19.27);
				return fract(vec3(p.x * p.y, p.z*p.x, p.y*p.z));
			}

			//Very happy with this star function, cheap and smooth
			vec3 stars(in vec3 p)
			{
				vec3 c = vec3(0.);
				float res = u_resolution.x * 0.2 ;
				vec2 mouse = abs(u_mouse.xy/u_resolution.xy - .5)*2.;

				for (float i=0.;i<5.;i++)
				{

					vec3 q = fract(p*(.15*res))-  sin(u_time * noise(vec2(p.xy)));

					vec3 id = floor(p*(.15*res));

					vec2 rn = hash33(id).xy;

					float c2 = 1.-smoothstep(0.,.6,length(q));

					c2 *= step(rn.x,.0005+i*i*0.001);

					c += c2*(mix(vec3(0.5,0.49,0.1),vec3(0.75,0.9,1.),rn.y)*0.25+0.75);

					p *= 1.4;
				}
				return c*c*.7;


			}

			// Function Bezier Curve --------------------------------
			float bezierCurve(float p0, float p1, float p2, float p3 ,float t)
			{
				return (pow(1.0-t,3.0)*p0 + 3.0* pow(1.0-t,2.0)*t*p1 + (1.0-t)*3.0*pow(t,2.0)*p2 + pow(t,3.0)*p3);

				
			}


			void main()
			{	

				// Reglages pour les effects de souris / Camera -------------------------------------------

					//Position de l origine de plan au centre ecran -------------------------------
				vec2 q = gl_FragCoord.xy / u_resolution.xy; //q= le normalise la dimention ecran  de l ecran    0 <> 1
				vec2 p = q - 0.5;  // centre de l ecran en fonction du ration    -0.5 <> 0.5


				p.x*=u_resolution.x/u_resolution.y;    // raport le ratio a x pour pour avoir des formes rondes. 
				

				
				
				float mox = u_mouse.x / u_resolution.x  ;       // position souris en x 
				float moy = u_mouse.y / u_resolution.y  ;      // position souris en y
				
				// position la camera en X pour voir le terrain 
				mox += 10.;


				// posCamBase   =2  // valeur ajouter position cam Base 
				float posCamBase = 2.; 


			//vec2 moStart =  vec2(mox , moy +posCamBase) ;

			// position final de la camera pour apparition titre 
				vec2 moEnd =  vec2(mox , clamp(moy,.75, 1.)) ;

				// vecteur 2  mo pout afficher les coordonner de la camera 
				vec2 mo = vec2(mox , moy +posCamBase)  ;


				// curve animation pour transition plan1 to 2 

				float easing = bezierCurve(0.,1.,1.,1., u_easing);
				
			
				// mix/ Animation entre plan1 yo 2 
				mo =  mix(mo ,  moEnd , easing	);


				//mo = moEnd;
				


				// Rotation de la Camera en function du Temps -----------------------------------

				//vec3 ro = vec3(650., sin(u_time*0.2)*0.25+10.,-u_time);
				
				// Enlever le mouvement sur y pour les decalage de map 
				vec3 ro = vec3(600.0, 10.,mo.x);
				
				vec3 eye = normalize(vec3(cos(mo.x),-0.5+mo.y,sin(mo.x)));
				vec3 right = normalize(vec3(cos(mo.x+1.5708),0.,sin(mo.x+1.5708)));
				vec3 up = normalize(cross(right, eye));
				vec3 rd = normalize((p.x*right + p.y*up)*1.05 + eye);
				rd.y += abs(p.x*p.x*0.015);
				rd = normalize(rd);
				
				float count = 0.;

				float rz = march(ro,rd, count);
				
				vec3 scatt = scatter(ro, rd);
				
				vec3 bg = stars(rd)*(1.0-clamp(dot(scatt, vec3(1)),0.,1.));

				vec3 col = bg;
				
				vec3 pos = ro+rz*rd;
				vec3 nor= normal( pos, rz );
				// calcul des couches en fonction de la profondeur
				if ( rz < FAR )
				{
					nor = bump(pos,nor,rz);
					float amb = clamp( 0.5+0.5*nor.y, 0.0, 1.0 );
					float dif = clamp( dot( nor, lgt ), 0.0, 1.0 );
					float bac = clamp( dot( nor, normalize(vec3(-lgt.x,0.0,-lgt.z))), 0.0, 1.0 );
					float spe = pow(clamp( dot( reflect(rd,nor), lgt ), 0.0, 1.0 ),500.);
					float fre = pow( clamp(1.0+dot(nor,rd),0.0,1.0), 2.0 );
					vec3 brdf = 1.*amb*vec3(0.10,0.11,0.12);
					brdf += bac*vec3(0.15,0.05,0.0);
					brdf += 2.3*dif*vec3(0.15,0.05,0.0);

					col = vec3(0.25,0.25,0.3);
					float crv = curv(pos, 2.)*1.;
					float crv2 = curv(pos, .4)*2.5;
					
					col += clamp(crv*0.9,-1.,1.)*vec3(0.25,.6,.5);
					col = col*brdf + col*spe*.1 +.1*fre*col;
					col *= crv*1.+1.;
					col *= crv2*1.+1.;
				}
				
				col = fog(ro, rd, col, rz);
				col = mix(col,bg,smoothstep(FAR-150., FAR, rz));
				col += scatt;
				
				col = pow( col, vec3(1,0.9,0.9) );
				col = mix(col, smoothstep(0.,1.,col), 0.2);
				col *= pow( 16.0*q.x*q.y*(1.0-q.x)*(1.0-q.y), 0.1)*0.9+0.1;
				
				//vec4 past = texture2D(u_buffer0, q);
				//float tOver = clamp(u_delta-(1./60.),0.,1.);
				
				//if (count/pow(rz, 0.65) > 3.3) col = mix(col, past.rgb, clamp(1.0-u_resolution.x*0.0003,0.,1.));
				//if (count/pow(rz, 0.65) > 3.3) col = mix(col, past.rgb, clamp(0.85-u_delta*7.,0.,1.));
				
				gl_FragColor = vec4(col, 1.0);
			}

	#else

		void main() {
			
			
			
			//gl_FragColor = texture2D(u_buffer0, gl_FragCoord.xy / u_resolution.xy);
			
			//debug
			
			vec4 shader = texture2D(u_buffer0, gl_FragCoord.xy / u_resolution.xy);

			//Mix pour la transition au noir 
			gl_FragColor = mix(shader ,  vec4(0.,0.,0.,1.) , u_blackTransition*0.1);

			//debug souris
			//vec2 Mouse = abs(u_mouse.xy/u_resolution.xy - .5);
			
			//Mouse.x *= u_resolution.x/u_resolution.y;
			//Mouse.y *= u_resolution.x/u_resolution.y;

			//gl_FragColor = vec4(u_easing,0.,.5, 1.0);


			}



	#endif

