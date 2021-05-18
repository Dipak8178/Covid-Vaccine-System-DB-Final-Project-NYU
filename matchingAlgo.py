import mysql.connector
from mysql.connector import Error
from datetime import datetime

def getAppt():
    try:
        connection = mysql.connector.connect(host='localhost',
                                            database='CovidVaccineSystem',
                                            user='root',
                                            password='Fiffat123!')
        cursor = connection.cursor()

        query2 = ("CALL findAllApptMatches()")
        cursor.execute(query2)
        pg_1 = {}
        pg_2 = {}
        pg_3 = {}
        pg_4 = {}
        
        seen = set()
        matched = []

        for patientId, apptId, pg, dist, rowrank in cursor:
            if (pg == '1'):
                if patientId not in pg_1:
                    pg_1[patientId] = {apptId: int(rowrank)}
                elif(len(pg_1[patientId]) == 5 ):
                    continue
                else:
                    pg_1[patientId][apptId] = int(rowrank)
            if (pg == '2'):
                if patientId not in pg_2:
                    pg_2[patientId] = {apptId: int(rowrank)}
                elif(len(pg_2[patientId]) == 3):
                    continue
                else:
                    pg_2[patientId][apptId] = int(rowrank)
            if (pg == '3'):
                if patientId not in pg_3:
                    pg_3[patientId] = {apptId: int(rowrank)}
                elif(len(pg_3[patientId]) == 3):
                    continue
                else:
                    pg_3[patientId][apptId] = int(rowrank)
            if (pg == '4'):
                if patientId not in pg_4:
                    pg_4[patientId] = {apptId: int(rowrank)}
                elif(len(pg_4[patientId]) == 3):
                    continue
                else:
                    pg_4[patientId][apptId] = int(rowrank)
  
        for key, val in pg_1.items():
            for k, v in val.items():
                if k not in seen:
                    matched.append([key,k,v])
                    seen.add(k)
                    break
        for key, val in pg_2.items():
            for k, v in val.items():
                if k not in seen:
                    matched.append([key,k,v])
                    seen.add(k)
                    break
        for key, val in pg_3.items():
            for k, v in val.items():
                if k not in seen:
                    matched.append([key,k,v])
                    seen.add(k)
                    break
        for key, val in pg_4.items():
            for k, v in val.items():
                if k not in seen:
                    matched.append([key,k,v])
                    seen.add(k)
                    break
    except Error as e:
        print("Error while connecting to MySQL", e)
    finally:
        if connection.is_connected():
            cursor.close()
            connection.close()
            print("MySQL connection is closed")
        return matched

if __name__ == "__main__":
    matchings = getAppt()
    print(matchings)
    try:
        connection = mysql.connector.connect(host='localhost',
                                            database='CovidVaccineSystem',
                                            user='root',
                                            password='Fiffat123!')
        cursor = connection.cursor()
        query = "INSERT INTO PatientAppointmentOffer(appointmentId, patientId, status, dateOfferSent) VALUES"
        

        now = datetime.now()
        formatted_date = now.strftime('%Y-%m-%d %H:%M:%S')

        for i in range(len(matchings)):
            if i < len(matchings)-1:
                query = query + "('"+str(matchings[i][1])+"', '"+ str(matchings[i][0]) + "', 'notified', '"+ formatted_date +"'),"
            else:
                query = query + "('"+str(matchings[i][1])+"', '"+ str(matchings[i][0]) + "', 'notified', '"+ formatted_date +"');"

        print(query)

        cursor.execute(query)
        connection.commit()


    except Error as e:
        print("Error while connecting to MySQL", e)
    finally:
        if connection.is_connected():
            cursor.close()
            connection.close()
            print("MySQL connection is closed")


