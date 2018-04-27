#include "osa_base.h"


static char encoding_table[] = {'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H',
								'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P',
								'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X',
								'Y', 'Z', 'a', 'b', 'c', 'd', 'e', 'f',
								'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n',
								'o', 'p', 'q', 'r', 's', 't', 'u', 'v',
								'w', 'x', 'y', 'z', '0', '1', '2', '3',
								'4', '5', '6', '7', '8', '9', '+', '/'};
char decoding_table [255];
int mod_table[] = {0, 2, 1};



void build_decoding_table() {


	int i;
	for ( i = 0; i < 64; i++) {
		decoding_table[(unsigned char) encoding_table[i]] = i;
	}
}

char *base64_encode(POOL *p, const unsigned char *data,
					size_t *output_length) {

	size_t input_length=strlen(data);

	*output_length = 4 * ((input_length + 2) / 3);

	char *encoded_data = PCALLOC(p, *output_length+1);
	//memset(encoded_data, 0, *output_length+1);
	if (encoded_data == NULL) return NULL;

	int i,j;
	for ( i = 0, j = 0; i < input_length;) {
		int octet_a = i < input_length ? data[i++] : 0;
		int octet_b = i < input_length ? data[i++] : 0;
		int octet_c = i < input_length ? data[i++] : 0;

		int triple = (octet_a << 0x10) + (octet_b << 0x08) + octet_c;

		encoded_data[j++] = encoding_table[(triple >> 3 * 6) & 0x3F];
		encoded_data[j++] = encoding_table[(triple >> 2 * 6) & 0x3F];
		encoded_data[j++] = encoding_table[(triple >> 1 * 6) & 0x3F];
		encoded_data[j++] = encoding_table[(triple >> 0 * 6) & 0x3F];
	}

	for ( i = 0; i < mod_table[input_length % 3]; i++)

	encoded_data[*output_length - 1 - i] = '=';
	encoded_data[*output_length]=0;
	return encoded_data;
}


unsigned char *base64_decode(const char *data,
							 size_t *output_length,
							 unsigned char *decoded_data) {

	size_t input_length=strlen(data);

	if (input_length % 4 != 0) return NULL;

	*output_length = input_length / 4 * 3;
	if (data[input_length - 1] == '=') (*output_length)--;
	if (data[input_length - 2] == '=') (*output_length)--;

	if (decoded_data == NULL) return NULL;

	int i,j;
	for ( i = 0, j = 0; i < input_length;) {

			int sextet_a = data[i] == '=' ? 0 & i++ : decoding_table[data[i++]];
			int sextet_b = data[i] == '=' ? 0 & i++ : decoding_table[data[i++]];
			int sextet_c = data[i] == '=' ? 0 & i++ : decoding_table[data[i++]];
			int sextet_d = data[i] == '=' ? 0 & i++ : decoding_table[data[i++]];

			int triple = (sextet_a << 3 * 6)
			+ (sextet_b << 2 * 6)
			+ (sextet_c << 1 * 6)
			+ (sextet_d << 0 * 6);

			if (j < *output_length) decoded_data[j++] = (triple >> 2 * 8) & 0xFF;
			if (j < *output_length) decoded_data[j++] = (triple >> 1 * 8) & 0xFF;
			if (j < *output_length) decoded_data[j++] = (triple >> 0 * 8) & 0xFF;
	}

	//base64_cleanup();
	decoded_data[*output_length]=0;
	return decoded_data;
}


void split(char *str, char delimiter, spliting *s){

	int i=0;
	int wordLen=0;
	char *ptr=str;
	s->tokensCount=0;
	while (str[i]){
		if (str[i]==delimiter){
			//confMapping[i]=0;
			strncpy(s->tokens[s->tokensCount], ptr, wordLen);
			s->tokens[s->tokensCount][wordLen]='\0';
			ptr=str+i;
			ptr++;
			wordLen=0;
			s->tokensCount++;
		}else{
			wordLen++;
		}
		i++;
	}
	strcpy(s->tokens[s->tokensCount], ptr);
	s->tokensCount++;
}

char *replace(char *st, char *orig, char *repl) {
	static char buffer[4096];
	char *ch;
	if (!(ch = strstr(st, orig))){
		return st;
	}
	strncpy(buffer, st, ch-st);  
	buffer[ch-st] = 0;
	sprintf(buffer+(ch-st), "%s%s", repl, ch+strlen(orig));
	return buffer;
}




/*--------------------------------------------------------------------------------------------------*/
/*                 void dumpHTMLError(request_rec *r, char *errMSG)                                 */
/*--------------------------------------------------------------------------------------------------*/
/* Error management for Nursery's mediations                                                        */
/* Display error text in main body for request as HTML                                              */
/*--------------------------------------------------------------------------------------------------*/
/* IN:                                                                                              */
/*        request_rec *r: apache request                                                            */
/*        char *errMSG: message to diplay                                                           */
/*        int status: HTTP status to set for response                                               */
/*--------------------------------------------------------------------------------------------------*/
/* RETURN: effectiove HTTP status (i.e 500 for SOAP, else status parameter                          */
/*         DONE                                                                                     */
/*--------------------------------------------------------------------------------------------------*/
static void dumpHTMLError(request_rec *r, char *errMSG){
	char strHttpBody[2000];

	strHttpBody[0]=0;

	strcat(strHttpBody,"<h1>An error has occurred</h1>\n");
	strcat(strHttpBody,"<table>\n");
	strcat(strHttpBody,"	<tr><td>Error code:</td><td>-1</td></tr>\n");
	strcat(strHttpBody,"	<tr><td>Error label:</td><td>");
	strcat(strHttpBody,errMSG);
	strcat(strHttpBody,"</td></tr>\n");
	strcat(strHttpBody,"</table>\n");

	r->content_type="text/html";
	ap_rputs(strHttpBody, r);
}

/*--------------------------------------------------------------------------------------------------*/
/*                 void dumpTextError(request_rec *r, char *errMSG)                                 */
/*--------------------------------------------------------------------------------------------------*/
/* Error management for Nursery's mediations                                                        */
/* Display error text in main body for request as HTML                                              */
/*--------------------------------------------------------------------------------------------------*/
/* IN:                                                                                              */
/*        request_rec *r: apache request                                                            */
/*        char *errMSG: message to diplay                                                           */
/*        int status: HTTP status to set for response                                               */
/*--------------------------------------------------------------------------------------------------*/
/* RETURN: effectiove HTTP status (i.e 500 for SOAP, else status parameter                          */
/*         DONE                                                                                     */
/*--------------------------------------------------------------------------------------------------*/
static void dumpTextError(request_rec *r, char *errMSG){
	char strHttpBody[2000];

	strHttpBody[0]=0;

	strcat(strHttpBody,"Error code: -1\n");
	strcat(strHttpBody,"Error label: ");
	strcat(strHttpBody,errMSG);

	r->content_type="text/plain";
	ap_rputs(strHttpBody, r);

}

/*--------------------------------------------------------------------------------------------------*/
/*                 void dumpJSONFault(request_rec *r, char *errMSG)                                 */
/*--------------------------------------------------------------------------------------------------*/
/* Error management for Nursery's mediations                                                        */
/* Display error text in main body for request as JSON                                              */
/*--------------------------------------------------------------------------------------------------*/
/* IN:                                                                                              */
/*        request_rec *r: apache request                                                            */
/*        char *errMSG: message to diplay                                                           */
/*        int status: HTTP status to set for response                                               */
/*--------------------------------------------------------------------------------------------------*/
/* RETURN: effectiove HTTP status (i.e 500 for SOAP, else status parameter                          */
/*         DONE                                                                                     */
/*--------------------------------------------------------------------------------------------------*/
static void dumpJSONError(request_rec *r, char *errMSG){
	char strHttpBody[2000];

	char errorMessage[255];
	strcpy(errorMessage,replace(errMSG,"\n","\\n"));
	strcpy(errorMessage,replace(errorMessage,"\"","\\\""));

	strHttpBody[0]=0;

	strcat(strHttpBody,"{\n");
	strcat(strHttpBody,"    \"code\": \"-1\",\n");
	strcat(strHttpBody,"    \"label\": \"");
	strcat(strHttpBody,errorMessage);
	strcat(strHttpBody,"\"\n");
	strcat(strHttpBody,"}\n");

	r->content_type="application/json";
	ap_rputs(strHttpBody, r);

}

/*--------------------------------------------------------------------------------------------------*/
/*                 void dumpXMLFault(request_rec *r, char *errMSG){                                 */
/*--------------------------------------------------------------------------------------------------*/
/* Error management for Nursery's mediations                                                        */
/* Display error text in main body for request as XML                                               */
/*--------------------------------------------------------------------------------------------------*/
/* IN:                                                                                              */
/*        request_rec *r: apache request                                                            */
/*        char *errMSG: message to diplay                                                           */
/*        int status: HTTP status to set for response                                               */
/*--------------------------------------------------------------------------------------------------*/
/* RETURN: effectiove HTTP status (i.e 500 for SOAP, else status parameter                          */
/*         DONE                                                                                     */
/*--------------------------------------------------------------------------------------------------*/
static void dumpXMLError(request_rec *r, char *errMSG){
	char strHttpBody[2000];

	strHttpBody[0]=0;
	strcat(strHttpBody,"<?xml version='1.0' encoding='UTF-8'?>\n");
	strcat(strHttpBody,"<osa>\n");
	strcat(strHttpBody,"	<code>-1</code>\n");
	strcat(strHttpBody,"	<label>");
	strcat(strHttpBody,errMSG);
	strcat(strHttpBody,"</osa>\n");

	r->content_type="text/xml";
	ap_rputs(strHttpBody, r);

}

/*--------------------------------------------------------------------------------------------------*/
/*                 void dumpSOAPFault(request_rec *r, char *errMSG){                                */
/*--------------------------------------------------------------------------------------------------*/
/* Error management for Nursery's mediations                                                        */
/* Display error text in main body for request as SOAP Fault                                        */
/*--------------------------------------------------------------------------------------------------*/
/* IN:                                                                                              */
/*        request_rec *r: apache request                                                            */
/*        char *errMSG: message to diplay                                                           */
/*        int status: HTTP status to set for response                                               */
/*--------------------------------------------------------------------------------------------------*/
/* RETURN: effectiove HTTP status (i.e 500 for SOAP, else status parameter                          */
/*         DONE                                                                                     */
/*--------------------------------------------------------------------------------------------------*/
void dumpSOAPFault(request_rec *r, char *errMSG){
	char strHttpBody[2000];

	strHttpBody[0]=0;
	strcat(strHttpBody,"<?xml version='1.0' ?>\n");
	strcat(strHttpBody,"<env:Envelope xmlns:env='http://schemas.xmlsoap.org/soap/envelope/'>\n");
	strcat(strHttpBody,"	<env:Body>\n");
	strcat(strHttpBody,"		<env:Fault>\n");
	strcat(strHttpBody,"			<faultcode>env:Server</faultcode>\n");
	strcat(strHttpBody,"			<faultstring>");
	strcat(strHttpBody,"                    	");
	strcat(strHttpBody, errMSG);
	strcat(strHttpBody,"                    </faultstring>\n");
	strcat(strHttpBody,"		</env:Fault>\n");
	strcat(strHttpBody,"	</env:Body>\n");
	strcat(strHttpBody,"</env:Envelope>\n");

	r->content_type="text/xml";


	ap_rputs(strHttpBody, r);

}




/*--------------------------------------------------------------------------------------------------*/
/*                 int renderErrorBody(request_rec *r, char *errMSG, int status)                    */
/*--------------------------------------------------------------------------------------------------*/
/* Error management for Nursery's mediations                                                        */
/* Display error text in main body for request depending on reqiested formet (SOAP, XML, JSON....)  */
/*--------------------------------------------------------------------------------------------------*/
/* IN:                                                                                              */
/*        request_rec *r: apache request                                                            */
/*        char *errMSG: message to diplay                                                           */
/*        int status: HTTP status to set for response                                               */
/*--------------------------------------------------------------------------------------------------*/
/* RETURN: effectiove HTTP status (i.e 500 for SOAP, else status parameter                          */
/*         DONE                                                                                     */
/*--------------------------------------------------------------------------------------------------*/
int renderErrorBody(request_rec *r, char *errMSG, int status){

	int rc = status;
	char *soapHeader = apr_pstrdup(r->pool, apr_table_get(r->headers_in, "SOAPAction"));
	char *acceptHeader  = apr_pstrdup(r->pool, apr_table_get(r->headers_in, "Accept"));


	if (soapHeader){
		rc = 500;
		dumpSOAPFault(r, errMSG);
	}else{
		spliting acceptList;
		split(acceptHeader,',', &acceptList);
		int i;
		int jobDone=0;
		for (i=0;i<acceptList.tokensCount && !jobDone;i++){
			if (strstr(acceptList.tokens[i],"html")){
				dumpHTMLError(r, errMSG);
				jobDone=1;
			}else if (strstr(acceptList.tokens[i],"json")){
										dumpJSONError(r, errMSG);
				jobDone=1;
						}else if (strstr(acceptList.tokens[i],"xml")){
										dumpXMLError(r, errMSG);
				jobDone=1;
						}else{
				dumpTextError(r, errMSG);
				jobDone=1;
			}
		}
	}
	return rc;
}





/*--------------------------------------------------------------------------------------------------*/
/*                 int osa_error(request_rec *r, char *errMSG, int status)                      */
/*--------------------------------------------------------------------------------------------------*/
/* Error management for OSA's mediations                                                        */
/* Display error text in main body for request and as a HTTP HEADER                                 */
/*--------------------------------------------------------------------------------------------------*/
/* IN:                                                                                              */
/*        request_rec *r: apache request                                                            */
/*        char *errMSG: message to diplay                                                           */
/*        int status: HTTP status to set for response                                               */
/*--------------------------------------------------------------------------------------------------*/
/* RETURN: apache processing status                                                                 */
/*         DONE                                                                                     */
/*--------------------------------------------------------------------------------------------------*/
int osa_error(request_rec *r, char *errMSG, int status){

	LOG_ERROR_1(APLOG_ERR, 0, r,"%s", errMSG);
	r->status= renderErrorBody(r, errMSG, status);
	apr_table_set(r->err_headers_out, OSA_ERROR_HEADER, errMSG);


	return DONE;
}

